## The Accurate Tools Shop

The Job Management and Process Automation System is being developed to streamline and automate the job creation, and tracking process for The Accurate Tool Shop. The system will improve operational eDiciency by managing workflows from purchase orderto delivery. The platform will track job requests, monitor associated costs, generate required documentation, and support the tracking of tasks in real time. The system will also provide dashboards, reporting capabilities, and integrate vendor management functionalities to ensure timely deliveries and data accuracy. The solution should cater to both the internal production team and management with a user-friendly interface.

## API Documentation Link

https://documenter.getpostman.com/view/34356572/2sAYdbNYaz

## Table of Contents

1. [Core Features](#core-features)
2. [API Endpoints](#api-endpoints)
    - [Authentication](#authentication)
    - [Purchase Orders](#purchase-orders)
    - [Inventory Management](#inventory-management)
    - [Vendor and Client Management](#vendor-and-client-management)
    - [Task and Job Management](#task-and-job-management)
    - [Notifications](#notifications)
3. [Export and Import Features](#export-and-import-features)
4. [Middleware and Security](#middleware-and-security)
5. [Contributing](#contributing)
6. [License](#license)

---

## Core Features

1. Managed user authentication and authorization for both users and admins.
2. Handled purchase order creation, updating, deletion, and reporting.
3. Managed inventories, vendors, and clients efficiently.
4. Facilitated task creation, assignment, tracking, and job status updates.

---

## API Endpoints

### Authentication

-   **Register:** Allows new user registration.
-   **Login:** Supports login for users and admins.
-   **Logout:** Logs out authenticated users.
-   **Password Management:** Supports password reset via OTP.

### Purchase Orders

-   **CRUD Operations:** Create, update, delete, and view purchase orders.
-   **Assign Members:** Assign members to purchase orders.
-   **Search and Export:** Search orders and export them in CSV, Excel, or PDF.

### Inventory Management

-   **CRUD Operations:** Manage materials and track inventory status.
-   **Filters:** View available, unavailable, and ordered materials.

### Vendor and Client Management

-   **CRUD Operations:** Manage vendors and clients efficiently.
-   **Search and Export:** Search and export data in CSV and PDF formats.

### Task and Job Management

-   **CRUD Operations:** Create, update, assign, and delete tasks.
-   **Job Status:** Update job progress and view job statistics.

### Notifications

-   **Unread Notifications:** Fetch unread notifications for users.
-   **Read Notifications:** Fetch read notifications.
-   **All Notifications:** Fetch all notifications for a user.

---

## Export and Import Features

-   **Purchase Orders:** Export to CSV, Excel, PDF; Import from CSV.
-   **Clients and Vendors:** Export to CSV and PDF.
-   **Reports:** Generate downloadable reports for admin analysis.

---

## Middleware and Security

-   **Sanctum:** Used for API authentication.
-   **Custom Middleware:** Ensures only admins can access certain routes.
-   **Throttling:** Prevents abuse of authentication routes.

---

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any improvements.

---

## License

This project is licensed under the MIT License.

---
