---
description: Auto-apply skills based on task type for Laravel Administration System
---

# Auto Skills Workflow

## Overview
This workflow enables automatic skill application based on task type. The agent MUST automatically read and apply relevant skills from `.agent/skills/skills/` without user intervention.

## Skill Mapping by Task Type

### 1. PHP/Laravel Backend Development
**When working on:** Controllers, Models, Services, Repositories, Middleware, Requests, Policies
**Auto-apply skills:**
- `php-pro` - Modern PHP patterns and best practices
- `software-architecture` - Clean Architecture & DDD principles
- `clean-code` - Pragmatic coding standards

**Before editing any PHP file, read:**
```
.agent/skills/skills/php-pro/SKILL.md
.agent/skills/skills/software-architecture/SKILL.md
.agent/skills/skills/clean-code/SKILL.md
```

### 2. UI/UX & Frontend Development
**When working on:** Blade templates, CSS, JavaScript, Views, Layouts, Components
**Auto-apply skills:**
- `ui-ux-pro-max` - Design intelligence with 50+ styles
- `tailwind-patterns` - Tailwind CSS patterns
- `frontend-design` - Frontend design guidelines

**Before editing any Blade/CSS/JS file, read:**
```
.agent/skills/skills/ui-ux-pro-max/SKILL.md
.agent/skills/skills/tailwind-patterns/SKILL.md
```

### 3. Database & Migration
**When working on:** Migrations, Seeders, Database queries, Eloquent relationships
**Auto-apply skills:**
- `database-design` - Database design principles
- `sql-optimization-patterns` - SQL optimization

**Before editing any migration/database file, read:**
```
.agent/skills/skills/database-design/SKILL.md
```

### 4. API Development
**When working on:** API routes, API controllers, Resources, API authentication
**Auto-apply skills:**
- `api-design-principles` - RESTful API design
- `api-security-best-practices` - API security

**Before editing any API file, read:**
```
.agent/skills/skills/api-design-principles/SKILL.md
.agent/skills/skills/api-security-best-practices/SKILL.md
```

### 5. Testing
**When working on:** Tests, Test cases, Feature tests, Unit tests
**Auto-apply skills:**
- `test-driven-development` - TDD workflow
- `testing-patterns` - Testing patterns

**Before writing any test, read:**
```
.agent/skills/skills/test-driven-development/SKILL.md
.agent/skills/skills/testing-patterns/SKILL.md
```

### 6. Security Implementation
**When working on:** Authentication, Authorization, Input validation, Security fixes
**Auto-apply skills:**
- `api-security-best-practices` - Security best practices
- `security-scanning-security-hardening` - Security hardening

**Before any security-related work, read:**
```
.agent/skills/skills/api-security-best-practices/SKILL.md
```

### 7. Documentation
**When working on:** README, docs, comments, API documentation
**Auto-apply skills:**
- `documentation-templates` - Documentation standards

## Auto-Detection Rules

The agent MUST automatically detect task type based on:

| File Pattern | Task Type | Skills to Apply |
|--------------|-----------|-----------------|
| `*.php` (Controllers) | Backend | php-pro, software-architecture, clean-code |
| `*.php` (Models) | Backend + Database | php-pro, database-design |
| `*.blade.php` | Frontend/UI | ui-ux-pro-max, tailwind-patterns |
| `*.css`, `*.scss` | Styling | ui-ux-pro-max, tailwind-patterns |
| `*.js`, `*.ts` | Frontend Logic | clean-code, javascript-pro |
| `*migration*.php` | Database | database-design, sql-optimization-patterns |
| `*test*.php` | Testing | test-driven-development, testing-patterns |
| `routes/api.php` | API | api-design-principles, api-security-best-practices |

## Execution Protocol

1. **Detect** - Identify file type and task category
2. **Load** - Read relevant SKILL.md files
3. **Apply** - Follow skill instructions during implementation
4. **Verify** - Use skill's verification checklist before completion

## Critical Rules

- ✅ ALWAYS read skill files BEFORE making changes
- ✅ APPLY skill guidelines during implementation
- ✅ USE skill checklists for verification
- ❌ NEVER skip skill application for any task
- ❌ NEVER ignore skill recommendations

## Laravel-Specific Guidelines

### Controllers
- Use Form Requests for validation
- Keep controllers thin (delegate to Services)
- Return proper HTTP status codes
- Use Resource classes for API responses

### Models
- Define fillable/guarded properties
- Use proper relationships
- Implement scopes for common queries
- Use accessors/mutators appropriately

### Views (Blade)
- Use components for reusable UI
- Apply Tailwind CSS with ui-ux-pro-max guidelines
- Ensure responsive design (mobile-first)
- Follow accessibility standards

### Services
- Single responsibility per service
- Inject dependencies via constructor
- Return typed responses
- Handle exceptions properly
