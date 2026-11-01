const db = require('../config/db');

exports.getTransactions = (req, res) => {
  try {
    const { type, category, date } = req.query;
    let sql = 'SELECT * FROM financial_transactions WHERE 1=1';
    const params = [];

    if (type && type !== 'all') {
      sql += ' AND type = ?';
      params.push(type);
    }
    if (category && category !== 'all') {
      sql += ' AND category = ?';
      params.push(category);
    }
    if (date) {
      sql += ' AND transaction_date LIKE ?';
      params.push(`${date}%`);
    }

    sql += ' ORDER BY id DESC LIMIT 200';
    const transactions = db.all(sql, params);
    res.json(transactions);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving financial records', error: error.message });
  }
};

exports.recordTransaction = (req, res) => {
  try {
    const { type, category, amount, reference_id, description } = req.body;
    if (!type || !category || !amount) {
      return res.status(400).json({ message: 'Type (income/expense), category, and amount are required' });
    }

    const provider_name = req.user ? req.user.full_name : 'Cashier Desk';
    const result = db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [type, category, amount, reference_id || 'CASH-TX', provider_name, description || 'Direct ledger entry']
    );

    const created = db.get('SELECT * FROM financial_transactions WHERE id = ?', [result.lastInsertRowid]);
    res.status(201).json({ message: 'Transaction recorded successfully', transaction: created });
  } catch (error) {
    res.status(500).json({ message: 'Error recording transaction', error: error.message });
  }
};

exports.getSummary = (req, res) => {
  try {
    const incomeRow = db.get("SELECT COALESCE(SUM(amount), 0) as total FROM financial_transactions WHERE type = 'income'");
    const expenseRow = db.get("SELECT COALESCE(SUM(amount), 0) as total FROM financial_transactions WHERE type = 'expense'");

    const totalIncome = incomeRow ? incomeRow.total : 0;
    const totalExpenses = expenseRow ? expenseRow.total : 0;
    const netProfit = totalIncome - totalExpenses;

    const categoryBreakdown = db.all(`
      SELECT category, type, SUM(amount) as total
      FROM financial_transactions
      GROUP BY category, type
    `);

    const recent = db.all('SELECT * FROM financial_transactions ORDER BY id DESC LIMIT 8');

    res.json({
      totalIncome,
      totalExpenses,
      netProfit,
      categoryBreakdown,
      recent
    });
  } catch (error) {
    res.status(500).json({ message: 'Error compiling financial summary', error: error.message });
  }
};

exports.payEmployeeSalary = (req, res) => {
  try {
    const { employee_id, notes } = req.body;
    const employee = db.get('SELECT * FROM users WHERE id = ?', [employee_id]);
    if (!employee) {
      return res.status(404).json({ message: 'Employee not found' });
    }

    const salary = employee.salary || 10000;
    const provider_name = req.user ? req.user.full_name : 'Chief Cashier';

    const result = db.run(
      `INSERT INTO financial_transactions (type, category, amount, reference_id, provider_name, description)
       VALUES ('expense', 'staff_salary', ?, ?, ?, ?)`,
      [salary, `PAYROLL-${employee.id}`, provider_name, `Monthly salary disbursement for ${employee.full_name} (${employee.position || employee.role}). ${notes || ''}`]
    );

    res.json({
      message: `Salary of ${salary} ETB successfully paid to ${employee.full_name}`,
      transactionId: result.lastInsertRowid
    });
  } catch (error) {
    res.status(500).json({ message: 'Error executing payroll', error: error.message });
  }
};
