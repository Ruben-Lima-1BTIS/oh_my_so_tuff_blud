# InternHub - Laravel Application

A modern Laravel application for managing internships, built with Supabase as the database backend.

## Migration from PHP to Laravel

This application has been migrated from a procedural PHP architecture to a modern Laravel framework with the following improvements:

### Architecture Changes

1. **MVC Pattern**: Follows Laravel's Model-View-Controller architecture
2. **Eloquent ORM**: Database queries use Laravel's Eloquent instead of raw PDO
3. **Blade Templating**: Views use Blade template engine for better organization
4. **Routing**: Centralized route management with middleware protection
5. **Service Container**: Dependency injection and IoC container
6. **Session Management**: Laravel's built-in session handling

### Database Migration

The database has been migrated to Supabase (PostgreSQL) with the following changes:

- **Auto-incrementing IDs → UUIDs**: All primary keys now use UUIDs for better security and distribution
- **Row Level Security (RLS)**: Implemented comprehensive RLS policies
- **Proper Indexing**: Added indexes for performance optimization
- **Foreign Key Constraints**: Proper relationships with CASCADE/RESTRICT rules

### Key Features

#### Multi-Role Authentication
- Students
- Supervisors
- Coordinators
- First-login password change enforcement

#### Student Features
- Dashboard with hours tracking and progress visualization
- Log internship hours with validation
- Submit weekly reports
- View approval status

#### Supervisor Features
- Dashboard with pending approvals
- Review and approve/reject hours
- Provide feedback on student reports
- Monitor student progress

#### Coordinator Features
- Class management dashboard
- Student progress tracking
- Report reviews
- Analytics and insights

## Directory Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   ├── Student/        # Student feature controllers
│   │   │   ├── Supervisor/     # Supervisor feature controllers
│   │   │   └── Coordinator/    # Coordinator feature controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── config/                     # Configuration files
│   ├── app.php
│   ├── database.php
│   └── supabase.php
├── resources/
│   └── views/
│       ├── layouts/            # Base layouts
│       ├── auth/               # Authentication views
│       ├── student/            # Student views
│       ├── supervisor/         # Supervisor views
│       └── coordinator/        # Coordinator views
└── routes/
    └── web.php                 # Web routes
```

## Environment Setup

1. Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

2. Configure Supabase credentials in `.env`:
```env
SUPABASE_URL=your_supabase_url
SUPABASE_ANON_KEY=your_anon_key
SUPABASE_SERVICE_KEY=your_service_key
```

3. Set your database connection:
```env
DB_CONNECTION=pgsql
DB_HOST=your_supabase_host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## Database Schema

### Tables

- **coordinators**: Coordinator user accounts
- **classes**: Academic classes managed by coordinators
- **students**: Student user accounts
- **companies**: Companies hosting internships
- **supervisors**: Company supervisor accounts
- **internships**: Internship programs
- **student_internships**: Student-internship assignments
- **supervisor_internships**: Supervisor-internship assignments
- **hours**: Student hour logs
- **reports**: Student weekly reports
- **conversations**: Message conversations
- **messages**: Individual messages

### Security

All tables have Row Level Security (RLS) enabled with policies ensuring:
- Users can only access their own data
- Coordinators can access data for their assigned classes
- Supervisors can access data for their assigned internships
- Proper authentication checks on all operations

## Models

### Eloquent Relationships

- **Student** belongs to Class, has many Hours and Reports
- **Coordinator** has many Classes
- **Supervisor** belongs to Company, has one Internship assignment
- **Class** belongs to Coordinator, has many Students
- **Internship** belongs to Company, has many Hour logs
- **Hour** belongs to Student and Internship
- **Report** belongs to Student

## Routes

### Public Routes
- `GET /` - Home page
- `GET /login` - Login form
- `POST /login` - Login submission

### Authenticated Routes
- `GET /change-password` - First-time password change
- `POST /change-password` - Password update
- `POST /logout` - Logout

### Student Routes (Prefix: `/student`)
- `GET /dashboard` - Student dashboard
- `GET /log-hours` - Hour logging form
- `POST /log-hours` - Submit hours
- `GET /submit-reports` - Report submission form
- `POST /submit-reports` - Upload report

### Supervisor Routes (Prefix: `/supervisor`)
- `GET /dashboard` - Supervisor dashboard
- Additional routes for approval workflows

### Coordinator Routes (Prefix: `/coordinator`)
- `GET /dashboard` - Coordinator dashboard
- Additional routes for class management

## Middleware

### AuthSession
Ensures user is authenticated via session before accessing protected routes.

### RoleMiddleware
Verifies user has the required role (student/supervisor/coordinator) for accessing specific routes.

## Controllers

### AuthController
Handles authentication, login, logout, and password changes with multi-role support.

### Student Controllers
- **DashboardController**: Student dashboard with statistics
- **HoursController**: Hour logging and viewing
- **ReportController**: Report submission and management

### Supervisor Controllers
- **DashboardController**: Supervisor overview with pending approvals

### Coordinator Controllers
- **DashboardController**: Class management and student monitoring

## Views

### Layouts
- `layouts/app.blade.php` - Main application layout with sidebar

### Authentication
- `auth/login.blade.php` - Login page
- `auth/change-password.blade.php` - Password change form

### Student Views
- `student/dashboard.blade.php` - Dashboard with charts
- `student/log-hours.blade.php` - Hour logging interface
- `student/submit-reports.blade.php` - Report submission

### Component Structure
All views use Tailwind CSS for styling and Chart.js for data visualization.

## Migration Benefits

1. **Security**: RLS policies, CSRF protection, password hashing with bcrypt
2. **Maintainability**: Clear separation of concerns, reusable components
3. **Scalability**: Eloquent relationships, query optimization, caching support
4. **Developer Experience**: Blade templating, route naming, middleware
5. **Testing**: Laravel's testing framework ready to use
6. **Modern Stack**: PHP 8.2+, PostgreSQL, Tailwind CSS

## Future Enhancements

- API endpoints for mobile app
- Real-time notifications
- Email integration for reports and approvals
- Advanced analytics and reporting
- File storage with Supabase Storage
- Multi-language support

## Security Considerations

- All passwords are hashed using bcrypt
- CSRF tokens on all forms
- Session-based authentication
- RLS policies on database level
- Input validation and sanitization
- Role-based access control

## Development

The application follows Laravel best practices:
- PSR-4 autoloading
- Environment-based configuration
- Dependency injection
- Service providers
- Middleware layers

## License

MIT License
