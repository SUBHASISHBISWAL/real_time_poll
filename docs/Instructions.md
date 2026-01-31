# Web Development Task 01: Real-Time Live Poll Platform

## 📋 Project Overview
[cite_start]Build a real-time web-based polling platform featuring IP restriction and admin moderation[cite: 62, 67].

- [cite_start]**Total Time:** 4 Hours (1 hour per module)[cite: 63].
- [cite_start]**Critical Rule:** Completion of Module 1 is required within the first 2 hours to continue[cite: 65].

## 🛠 Technology Stack
- [cite_start]**Framework:** Laravel (Routing, Auth, Structure, Views)[cite: 75, 76].
- [cite_start]**Logic:** Core PHP (Voting rules, IP validation, Rollback logic)[cite: 77, 79, 81].
- [cite_start]**Frontend:** HTML, CSS, Bootstrap, JS, jQuery[cite: 82, 83].
- [cite_start]**Requirement:** AJAX is mandatory for all interactions[cite: 84].
- [cite_start]**Database:** MySQL[cite: 86].

---

## 🏗 Modules Breakdown

### Module 1: Auth & Poll Display
- [cite_start]**Backend:** Basic login and poll creation (Question, Options, Status) [cite: 90-95].
- [cite_start]**Frontend:** Show active polls after login; click to view options[cite: 97, 98].
- [cite_start]**Constraint:** No page reloads for navigation; no hardcoded content[cite: 116, 117].

### Module 2: IP-Restricted Voting
- [cite_start]**Logic:** Enforce one vote per poll per IP using Core PHP[cite: 120, 121].
- [cite_start]**Data Capture:** Store Poll ID, Option, IP, and Timestamp [cite: 127-130].
- [cite_start]**UX:** Block repeat votes and show a message via AJAX without reload [cite: 184-186].

### Module 3: Real-Time Results
- [cite_start]**Requirement:** Results must update within ~1 second[cite: 197].
- [cite_start]**Mechanism:** Use AJAX-based updates without page refresh[cite: 195, 196].

### Module 4: Admin & Audit
- [cite_start]**Admin Tools:** View IPs and release them to remove their votes [cite: 202-206].
- [cite_start]**Audit Trail:** If an IP re-votes, admin must see the previous option/timestamp and the new one [cite: 265-271].
- [cite_start]**History:** All vote history must be preserved for the admin view[cite: 272].

---

## 🚫 Strictly Prohibited
- [cite_start]Using AI tools for code generation[cite: 283].
- [cite_start]Page reloads for voting, results, or IP release [cite: 286-289].
- [cite_start]Frontend-only vote restrictions[cite: 284].
- [cite_start]Deleting vote data without history[cite: 290].