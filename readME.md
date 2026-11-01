# 🏨 Grand Horizon Hotel Management System
> Built with **React** (Vite + Modern Vanilla CSS Luxury Design System) & **Node.js / Express** with Native Persistent SQLite & RBAC

Welcome to the **Enterprise Hotel Management System (EHMS)** — a modern, fully-featured, production-ready Full-Stack hospitality solution featuring:
- **Client Guest Portal**: Luxury landing page, online room & hall booking engine, booking status lookup, in-room dining, and guest reviews.
- **Enterprise Staff Portal**: Full Role-Based Access Control (RBAC) with unified authentication and dedicated dashboards for all 9 hotel actors.
- **One-Click Fast Persona Switcher**: Test and evaluate any role (Manager, Receptionist, Cashier, Host, Barman, Store, Purchaser, Staff, Customer) with a single click.

---

## ⚡ Quick Start

### 1. Install Dependencies
Run from the root directory:
```bash
npm run install:all
```
*(or `npm install --prefix backend && npm install --prefix frontend`)*

### 2. Start Both Dev Servers Concurrently
```bash
npm run dev
```
- **Backend API**: `http://localhost:5000` (Health check at `http://localhost:5000/api/health`)
- **Frontend App**: `http://localhost:5173`

---

## 👥 Roles & Workspaces

| Role | Primary Persona | Key Capabilities |
| :--- | :--- | :--- |
| 👑 **Manager** | `manager` | Executive KPI overview, employee CRUD, salary management, rooms/halls administration, attendance registry, financial ledger. |
| 🛎️ **Receptionist** | `receptionist` | Interactive visual Room Rack (Free/Occupied/Reserved/Maintenance), walk-in guest check-in, check-out, room & hall reservation management, guest directory. |
| 💰 **Cashier** | `cashier` | Central cash desk, settling guest room invoices, payroll disbursement to staff, manual deposit/withdrawal logging, daily cash balance. |
| 🍽️ **Host** | `host` | Touchscreen dining POS terminal for tablets/phones, table selection, food & beverage ordering with real-time stock deduction, active table orders stream. |
| 🍸 **Barman** | `barman` | Beverage inventory monitoring (craft beers, wines, spirits), drink order stream from hosts, stock restock requisitions, beverage sales tracking. |
| 📦 **Store** | `store` | Central warehouse inventory catalog, low-stock warnings, department dispatch requisitions to Bar, Kitchen, and Housekeeping. |
| 🛒 **Purchaser** | `purchaser` | Procurement orders, vendor management, supply chain tracking, auto-restocking warehouse upon delivery receipt. |
| 🧹 **Staff** | `staff` | Housekeeping room turnovers, sanitization checklist, maintenance incident reporting, daily shift attendance log. |
| ✨ **Customer** | `guest` | Luxury guest portal, view active reservations, order in-room dining, review invoices, submit verified hotel feedback. |

> **Note:** Default password for all seeded staff accounts is `password123`. You can also use the **One-Click Persona Bar** at the top of the app to switch instantly without typing passwords.

---

## 📁 Project Architecture

```
hotel-management-system/
│
├── backend/
│   ├── config/
│   │   ├── db.js             # Native Node.js SQLite persistent storage
│   │   └── initDb.js         # Auto-schema generator and rich seed data
│   ├── controllers/          # Auth, Rooms, Halls, Reservations, POS, Inventory, Finance, Staff
│   ├── middlewares/          # JWT authentication and Role-Based Access Control (RBAC)
│   ├── routes/               # Express REST API routes (/api/...)
│   ├── app.js                # Express app configuration with CORS, JSON, and Morgan
│   ├── server.js             # Server entry point on port 5000
│   └── package.json
│
├── frontend/
│   ├── public/               # Static assets & luxury branding
│   ├── src/
│   │   ├── components/       # Navbar, Sidebar, RoleBanner, Modal, Badges, Tables
│   │   ├── context/          # AuthContext with session persistence and demo role switcher
│   │   ├── pages/
│   │   │   ├── Landing/      # Luxury guest showcase & online booking engine
│   │   │   ├── Auth/         # Staff login and one-click demo launcher
│   │   │   └── Dashboard/    # Dedicated dashboards for Manager, Receptionist, Host, etc.
│   │   ├── services/         # API client with automatic JWT token injection
│   │   ├── index.css         # Luxury gold & dark glassmorphism design system
│   │   ├── App.jsx           # Master page routing
│   │   └── main.jsx          # React 18 client entry
│   ├── vite.config.js        # Vite config with backend proxy to :5000
│   └── package.json
│
├── run-dev.js                # Concurrent launcher for frontend and backend
└── package.json              # Root package scripts
```

---

## 🛠️ Technology Stack

- **Frontend**: React 18, Vite, Lucide Icons, Vanilla CSS Luxury Design System (Dark Glassmorphism, Gold Hues, Fluid Responsive Grid)
- **Backend**: Node.js, Express.js, JWT (`jsonwebtoken`), Bcryptjs, CORS, Morgan
- **Database**: Native Node.js SQLite engine (`node:sqlite`) with foreign key constraints, zero-config persistence at `backend/data/hotel.db`, and seed automation.
