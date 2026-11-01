import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Modal from '../../components/Modal';
import { ClipboardList, CheckCircle, Clock, AlertTriangle, Plus, ShieldCheck } from 'lucide-react';

export default function StaffDashboard({ activeTab = 'tasks' }) {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);

  // Modal for Reporting Incident / Task
  const [isTaskModalOpen, setIsTaskModalOpen] = useState(false);
  const [taskForm, setTaskForm] = useState({
    title: '',
    room_or_area: 'Room 201',
    priority: 'medium',
    notes: 'Reported by Staff'
  });

  useEffect(() => {
    loadTasks();
  }, []);

  async function loadTasks() {
    try {
      const data = await api.getTasks();
      setTasks(data);
    } catch (err) {
      console.error('Error loading staff tasks:', err);
    } finally {
      setLoading(false);
    }
  }

  const handleUpdateStatus = async (taskId, newStatus) => {
    try {
      await api.updateTask(taskId, { status: newStatus });
      loadTasks();
    } catch (err) {
      alert(err.message || 'Error updating task');
    }
  };

  const handleCreateTask = async (e) => {
    e.preventDefault();
    try {
      await api.createTask({
        ...taskForm,
        role: 'Staff'
      });
      setIsTaskModalOpen(false);
      setTaskForm({ title: '', room_or_area: 'Room 201', priority: 'medium', notes: 'Reported by Staff' });
      loadTasks();
    } catch (err) {
      alert(err.message || 'Error creating task');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <div>
          <h3 style={{ fontSize: '1.25rem', fontWeight: '800' }}>Housekeeping & Maintenance Operations</h3>
          <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
            Track assigned room turnovers, sanitization, and facility maintenance.
          </p>
        </div>
        <button onClick={() => setIsTaskModalOpen(true)} className="btn btn-gold btn-sm">
          <Plus size={15} /> Report New Issue / Task
        </button>
      </div>

      <div className="glass-card">
        <h4 style={{ fontSize: '1.1rem', marginBottom: '16px' }}>Assigned Duty Checklist</h4>
        <div className="table-container">
          <table className="modern-table">
            <thead>
              <tr>
                <th>Task / Duty</th>
                <th>Area / Room</th>
                <th>Priority</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Due</th>
                <th>Update Progress</th>
              </tr>
            </thead>
            <tbody>
              {tasks.map(t => (
                <tr key={t.id}>
                  <td style={{ fontWeight: '700' }}>{t.title}</td>
                  <td>{t.room_or_area}</td>
                  <td>
                    <span className={`badge badge-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'info'}`}>
                      {t.priority}
                    </span>
                  </td>
                  <td>{t.assigned_to_name}</td>
                  <td>
                    <span className={`badge badge-${t.status === 'completed' ? 'success' : 'warning'}`}>
                      {t.status.replace('_', ' ')}
                    </span>
                  </td>
                  <td style={{ fontSize: '0.8rem', color: 'var(--text-dim)' }}>{t.due_date}</td>
                  <td>
                    <div style={{ display: 'flex', gap: '6px' }}>
                      {t.status !== 'in_progress' && t.status !== 'completed' && (
                        <button
                          onClick={() => handleUpdateStatus(t.id, 'in_progress')}
                          className="btn btn-outline btn-sm"
                          style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                        >
                          Start
                        </button>
                      )}
                      {t.status !== 'completed' && (
                        <button
                          onClick={() => handleUpdateStatus(t.id, 'completed')}
                          className="btn btn-emerald btn-sm"
                          style={{ padding: '3px 8px', fontSize: '0.75rem' }}
                        >
                          <CheckCircle size={13} /> Complete
                        </button>
                      )}
                      {t.status === 'completed' && (
                        <span style={{ fontSize: '0.75rem', color: 'var(--emerald)' }}>✓ Done</span>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Task / Incident Modal */}
      <Modal isOpen={isTaskModalOpen} onClose={() => setIsTaskModalOpen(false)} title="Report Housekeeping or Maintenance Issue">
        <form onSubmit={handleCreateTask}>
          <div className="form-group">
            <label className="form-label">Task / Issue Title *</label>
            <input
              type="text"
              required
              placeholder="e.g. Bathroom plumbing leak or VIP room linen change"
              className="form-control"
              value={taskForm.title}
              onChange={(e) => setTaskForm({ ...taskForm, title: e.target.value })}
            />
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
            <div className="form-group">
              <label className="form-label">Room / Area</label>
              <input
                type="text"
                placeholder="e.g. Room 301 or Grand Ballroom"
                className="form-control"
                value={taskForm.room_or_area}
                onChange={(e) => setTaskForm({ ...taskForm, room_or_area: e.target.value })}
              />
            </div>
            <div className="form-group">
              <label className="form-label">Priority</label>
              <select
                className="form-select"
                value={taskForm.priority}
                onChange={(e) => setTaskForm({ ...taskForm, priority: e.target.value })}
              >
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High (Urgent)</option>
              </select>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Notes</label>
            <textarea
              rows="2"
              className="form-control"
              value={taskForm.notes}
              onChange={(e) => setTaskForm({ ...taskForm, notes: e.target.value })}
            />
          </div>

          <button type="submit" className="btn btn-gold" style={{ width: '100%', marginTop: '12px' }}>
            Submit Issue to Roster
          </button>
        </form>
      </Modal>
    </div>
  );
}
