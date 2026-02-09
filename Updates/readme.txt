# System Update to Version 1.4.0 - Requirements

Before updating your system to version 1.4.0, **please ensure that you back up your database first**. This is a crucial step to avoid any potential data loss during the update process.

To update your eSchool SaaS - School Management System to version 1.4.0, please ensure that the following requirements are met:

1. **Laravel Framework 10.0**: The system now requires Laravel 10.0 for the new features and improvements. Make sure your server supports PHP version 8.1.0 or higher.

2. **VPS Server for Multi-Tenancy Database**: The update introduces enhancements to the multi-tenancy feature. A VPS server is required to manage multiple databases for each school efficiently.

3. **Database Root User or Necessary Permissions**: You will need root access or sufficient database permissions to create, modify, and delete databases as part of the multi-tenancy update.

4. **Max Upload Size**: Ensure that the `max_upload_size` in your server is set to a large enough value (e.g., 50MB or more) to support file uploads (Required only for updating new version).

5. **Max Execution Time & Max Input Time**: Set the `max_execution_time` in your server to at least 5000 seconds to handle large operations such as creating school databases, backups, imports, or batch processing.

**Important Notes:**
- **Do not refresh your page** during the update process until you receive a confirmation message from the system, as refreshing may interrupt the process and cause issues.

Once these requirements are fulfilled, you can proceed with updating your system to version 1.4.0.