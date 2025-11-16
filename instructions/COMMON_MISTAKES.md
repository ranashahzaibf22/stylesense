# Common AI Mistakes to Avoid

This file tracks recurring mistakes Claude makes during development.
Always reference this file in prompts to prevent repetition.

## Purpose

This document serves as a living record of common pitfalls and mistakes that AI assistants (particularly Claude) tend to make during the StyleSense.AI project development. By documenting these issues, we can:

1. Provide clear examples to reference in future prompts
2. Improve code quality and consistency
3. Reduce debugging time by avoiding known issues
4. Maintain best practices throughout the project

## How to Use This File

**When prompting Claude:**
- Reference this file at the start: "Review COMMON_MISTAKES.md and avoid documented issues"
- Add specific sections: "Don't repeat mistakes from section X in COMMON_MISTAKES.md"
- After finding a new recurring issue, document it here

**When updating this file:**
- Add the mistake with a clear title
- Provide wrong and correct examples
- Explain why it's wrong and the proper approach
- Include the date when first encountered

---

## Mistakes List

### Database & Models

#### (To be documented during development)

---

### API & Routes

#### (To be documented during development)

---

### Authentication & Security

#### (To be documented during development)

---

### Frontend & UI

#### (To be documented during development)

---

### Testing & Validation

#### (To be documented during development)

---

### Environment & Configuration

#### (To be documented during development)

---

### Machine Learning

#### (To be documented during development)

---

### Deployment & DevOps

#### (To be documented during development)

---

## Template for Adding New Mistakes

When you encounter a recurring mistake, add it using this template:

```markdown
### [Category] - [Brief Description]

**Date First Encountered:** YYYY-MM-DD  
**Frequency:** How often this occurs (Rare/Occasional/Common/Frequent)  
**Impact:** Low/Medium/High/Critical

**The Mistake:**
[Clear description of what was done wrong]

**Wrong Code Example:**
```python
# ❌ Wrong implementation
[code example]
```

**Correct Code Example:**
```python
# ✅ Correct implementation
[code example]
```

**Why It's Wrong:**
[Explanation of why this is problematic]

**How to Fix:**
[Step-by-step guide to correct the issue]

**Prevention:**
[How to avoid this mistake in the future]

**Related Issues:**
- Link to similar mistakes or related documentation
```

---

## Example Entry (For Reference)

### Security - Hardcoding Secrets in Code

**Date First Encountered:** 2024-11-16  
**Frequency:** Common  
**Impact:** Critical

**The Mistake:**
Hardcoding API keys, secret keys, or database credentials directly in Python files or configuration files that are committed to git.

**Wrong Code Example:**
```python
# ❌ Wrong - Hardcoded secrets
SECRET_KEY = 'my-secret-key-123'
DATABASE_URL = 'postgresql://user:password@localhost/db'
OPENWEATHER_API_KEY = 'abcdef123456'
```

**Correct Code Example:**
```python
# ✅ Correct - Using environment variables
import os
from dotenv import load_dotenv

load_dotenv()

SECRET_KEY = os.getenv('SECRET_KEY')
DATABASE_URL = os.getenv('DATABASE_URL')
OPENWEATHER_API_KEY = os.getenv('OPENWEATHER_API_KEY')

# Validate required variables
if not SECRET_KEY:
    raise ValueError("SECRET_KEY environment variable is required")
```

**Why It's Wrong:**
- Exposes sensitive credentials in version control
- Security vulnerability if repository is public
- Makes it difficult to use different values for dev/staging/prod
- Violates security best practices for FYP evaluation

**How to Fix:**
1. Move all secrets to `.env` file
2. Add `.env` to `.gitignore`
3. Create `.env.example` with placeholder values
4. Load environment variables using `python-dotenv`
5. Validate required variables on startup

**Prevention:**
- Always check `.gitignore` includes `.env`
- Use environment variables for all configuration
- Review SECURITY_RULES.md section 9 before configuration
- Run `git status` before commits to check for .env files

**Related Issues:**
- See SECURITY_RULES.md - Section 9: Environment Variables
- Related to deployment configuration mistakes

---

## Notes for Development Team

### When to Document a Mistake

Add a mistake to this file when:

1. **Repetition:** The same error occurs 2+ times
2. **Severity:** The mistake causes bugs, security issues, or breaks functionality
3. **Common Pattern:** It's a typical error that AI assistants make
4. **Teaching Value:** Documenting it will help prevent future occurrences

### When NOT to Document

Don't add to this file:

1. **One-time typos:** Simple syntax errors that won't recur
2. **Intentional experiments:** Testing different approaches
3. **External issues:** Problems caused by third-party services or libraries
4. **Already documented:** If it's already covered in another instruction file

### Maintenance

- **Weekly Review:** Check if new mistakes should be added
- **Monthly Cleanup:** Remove entries that are no longer relevant
- **Version Control:** Note when mistakes are resolved in project updates
- **Cross-reference:** Link to relevant sections in other instruction files

---

## Categories Reference

Use these categories when adding new mistakes:

1. **Database & Models** - SQLAlchemy, database schema, relationships
2. **API & Routes** - Flask routes, endpoints, request handling
3. **Authentication & Security** - JWT, passwords, permissions, IDOR
4. **Frontend & UI** - React, Next.js, TypeScript, components
5. **Testing & Validation** - Unit tests, integration tests, validation
6. **Environment & Configuration** - .env files, config, deployment settings
7. **Machine Learning** - Model training, predictions, data processing
8. **Deployment & DevOps** - Railway, Vercel, production issues
9. **Code Quality** - Linting, formatting, code organization
10. **Documentation** - Comments, docstrings, API docs
11. **Performance** - Optimization, caching, database queries
12. **Error Handling** - Exception handling, logging, error responses

---

## Statistics (To be updated during development)

- **Total Mistakes Documented:** 0
- **Critical Issues:** 0
- **High Priority:** 0
- **Medium Priority:** 0
- **Low Priority:** 0
- **Resolved Issues:** 0

---

## Quick Reference Prompts

Use these prompts when working with Claude:

### General Development
```
"Before implementing, review COMMON_MISTAKES.md to avoid documented issues, 
especially in [category] section."
```

### Specific Feature
```
"Implement [feature] following best practices. Check COMMON_MISTAKES.md 
sections on [relevant categories] to avoid known issues."
```

### Code Review
```
"Review this code against COMMON_MISTAKES.md. Identify if any documented 
mistakes are present and suggest corrections."
```

### After Finding Bug
```
"This bug matches a pattern. Add it to COMMON_MISTAKES.md under [category] 
with wrong/correct examples."
```

---

**Last Updated:** November 2024  
**Version:** 1.0  
**Status:** Living Document - Updated Throughout Development  
**Next Review:** When first development phase begins

---

## Contributing to This File

When you add a new mistake:

1. ✅ Use the provided template
2. ✅ Include both wrong and correct code examples
3. ✅ Explain clearly why it's wrong
4. ✅ Provide prevention tips
5. ✅ Update the statistics section
6. ✅ Commit with message: `docs: Add [mistake] to COMMON_MISTAKES.md`

When you resolve a documented mistake:

1. ✅ Mark it as "Resolved" with date
2. ✅ Keep the entry for historical reference
3. ✅ Update statistics
4. ✅ Commit with message: `docs: Mark [mistake] as resolved in COMMON_MISTAKES.md`

---

**Remember:** This file is meant to improve code quality and development efficiency. Keep it updated, reference it often, and learn from documented mistakes!
