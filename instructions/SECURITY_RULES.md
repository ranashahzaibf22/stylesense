# StyleSense.AI - Security Rules & Best Practices

**CRITICAL FOR FYP EVALUATION:** Security is a major grading component. These rules MUST be followed in all code.

## Table of Contents
1. [Password Hashing](#1-password-hashing)
2. [JWT Token Management](#2-jwt-token-management)
3. [Input Validation](#3-input-validation)
4. [IDOR Prevention](#4-idor-prevention)
5. [Rate Limiting](#5-rate-limiting)
6. [SQL Injection Prevention](#6-sql-injection-prevention)
7. [XSS Prevention](#7-xss-prevention)
8. [CORS Configuration](#8-cors-configuration)
9. [Environment Variables](#9-environment-variables)
10. [Error Handling](#10-error-handling)

---

## 1. Password Hashing

**Rule:** ALWAYS use bcrypt with minimum 12 rounds for password hashing.

### ✅ Correct Implementation

```python
# app/models/user.py
from werkzeug.security import generate_password_hash, check_password_hash

class User(db.Model):
    password_hash = db.Column(db.String(255), nullable=False)
    
    def set_password(self, password):
        # bcrypt method with 12 rounds minimum
        self.password_hash = generate_password_hash(
            password, 
            method='bcrypt'
        )
    
    def check_password(self, password):
        return check_password_hash(self.password_hash, password)
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - Storing plain text passwords
class User(db.Model):
    password = db.Column(db.String(255))  # ❌ NO!

# NEVER DO THIS - Weak hashing
import hashlib
password_hash = hashlib.md5(password.encode()).hexdigest()  # ❌ NO!

# NEVER DO THIS - Storing password directly
user.password = request.form['password']  # ❌ NO!
```

### Password Validation

```python
# app/utils/validators.py
import re

def validate_password(password):
    """
    Password must be:
    - At least 8 characters long
    - Contains uppercase and lowercase letters
    - Contains at least one digit
    - Contains at least one special character
    """
    if len(password) < 8:
        return False, "Password must be at least 8 characters long"
    
    if not re.search(r'[A-Z]', password):
        return False, "Password must contain at least one uppercase letter"
    
    if not re.search(r'[a-z]', password):
        return False, "Password must contain at least one lowercase letter"
    
    if not re.search(r'\d', password):
        return False, "Password must contain at least one digit"
    
    if not re.search(r'[!@#$%^&*(),.?":{}|<>]', password):
        return False, "Password must contain at least one special character"
    
    return True, "Password is valid"
```

---

## 2. JWT Token Management

**Rule:** Use short-lived access tokens (1 hour) and longer refresh tokens (7 days).

### ✅ Correct Configuration

```python
# config.py
from datetime import timedelta

class Config:
    # JWT Settings
    JWT_SECRET_KEY = os.getenv('JWT_SECRET_KEY')  # Must be in .env
    JWT_ACCESS_TOKEN_EXPIRES = timedelta(hours=1)  # 1 hour
    JWT_REFRESH_TOKEN_EXPIRES = timedelta(days=7)  # 7 days
    JWT_TOKEN_LOCATION = ['headers']
    JWT_HEADER_NAME = 'Authorization'
    JWT_HEADER_TYPE = 'Bearer'
    
    # Security
    JWT_COOKIE_SECURE = True  # HTTPS only in production
    JWT_COOKIE_CSRF_PROTECT = True
```

### ✅ Token Generation

```python
# app/routes/auth.py
from flask_jwt_extended import create_access_token, create_refresh_token

@bp.route('/login', methods=['POST'])
def login():
    data = request.get_json()
    
    user = User.query.filter_by(email=data['email']).first()
    if not user or not user.check_password(data['password']):
        return jsonify({
            'success': False,
            'error': 'Invalid credentials'
        }), 401
    
    # Create tokens
    access_token = create_access_token(identity=user.id)
    refresh_token = create_refresh_token(identity=user.id)
    
    return jsonify({
        'success': True,
        'data': {
            'access_token': access_token,
            'refresh_token': refresh_token,
            'user': user.to_dict()
        }
    }), 200
```

### ✅ Token Refresh

```python
# app/routes/auth.py
from flask_jwt_extended import jwt_required, get_jwt_identity

@bp.route('/refresh', methods=['POST'])
@jwt_required(refresh=True)
def refresh():
    user_id = get_jwt_identity()
    new_access_token = create_access_token(identity=user_id)
    
    return jsonify({
        'success': True,
        'data': {'access_token': new_access_token}
    }), 200
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - Token that never expires
JWT_ACCESS_TOKEN_EXPIRES = False  # ❌ NO!

# NEVER DO THIS - Very long access token
JWT_ACCESS_TOKEN_EXPIRES = timedelta(days=365)  # ❌ NO!

# NEVER DO THIS - No refresh token
# Only using access token without refresh mechanism  # ❌ NO!
```

---

## 3. Input Validation

**Rule:** Validate ALL user inputs on the server-side before processing.

### ✅ Correct Implementation

```python
# app/utils/validators.py
from flask import request
from functools import wraps

def validate_request(*required_fields):
    """Decorator to validate required fields in request"""
    def decorator(f):
        @wraps(f)
        def decorated_function(*args, **kwargs):
            data = request.get_json()
            
            if not data:
                return jsonify({
                    'success': False,
                    'error': 'No data provided'
                }), 400
            
            missing_fields = []
            for field in required_fields:
                if field not in data or not data[field]:
                    missing_fields.append(field)
            
            if missing_fields:
                return jsonify({
                    'success': False,
                    'error': f'Missing required fields: {", ".join(missing_fields)}'
                }), 400
            
            return f(*args, **kwargs)
        return decorated_function
    return decorator

# Usage
@bp.route('/register', methods=['POST'])
@validate_request('email', 'username', 'password')
def register():
    data = request.get_json()
    # Fields are guaranteed to exist here
    # ... rest of the code
```

### ✅ Email Validation

```python
import re

def validate_email(email):
    """Validate email format"""
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    if not re.match(pattern, email):
        return False, "Invalid email format"
    
    if len(email) > 120:
        return False, "Email too long"
    
    return True, "Valid email"
```

### ✅ Sanitize User Input

```python
import bleach

def sanitize_text(text, max_length=500):
    """Sanitize user text input"""
    if not text:
        return ""
    
    # Remove any HTML tags
    clean_text = bleach.clean(text, tags=[], strip=True)
    
    # Truncate to max length
    clean_text = clean_text[:max_length]
    
    return clean_text.strip()
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - No validation
@bp.route('/create-post', methods=['POST'])
def create_post():
    data = request.get_json()
    post = Post(content=data['content'])  # ❌ What if 'content' doesn't exist?
    db.session.add(post)

# NEVER DO THIS - Client-side validation only
# Relying on frontend validation without server-side checks  # ❌ NO!

# NEVER DO THIS - Accepting raw HTML
content = request.form['content']  # ❌ Could contain malicious scripts
```

---

## 4. IDOR Prevention

**Rule:** ALWAYS verify that the authenticated user owns the resource they're accessing.

### ✅ Correct Implementation

```python
# app/routes/wardrobe.py
from flask_jwt_extended import jwt_required, get_jwt_identity

@bp.route('/wardrobe/<int:item_id>', methods=['GET'])
@jwt_required()
def get_wardrobe_item(item_id):
    user_id = get_jwt_identity()
    
    # Verify ownership
    item = WardrobeItem.query.filter_by(
        id=item_id,
        user_id=user_id  # ✅ Check that item belongs to user
    ).first()
    
    if not item:
        return jsonify({
            'success': False,
            'error': 'Item not found or access denied'
        }), 404
    
    return jsonify({
        'success': True,
        'data': item.to_dict()
    }), 200

@bp.route('/wardrobe/<int:item_id>', methods=['DELETE'])
@jwt_required()
def delete_wardrobe_item(item_id):
    user_id = get_jwt_identity()
    
    item = WardrobeItem.query.filter_by(
        id=item_id,
        user_id=user_id  # ✅ Check ownership before delete
    ).first()
    
    if not item:
        return jsonify({
            'success': False,
            'error': 'Item not found or access denied'
        }), 404
    
    db.session.delete(item)
    db.session.commit()
    
    return jsonify({
        'success': True,
        'message': 'Item deleted successfully'
    }), 200
```

### ❌ Wrong Implementation (IDOR Vulnerability)

```python
# NEVER DO THIS - No ownership check
@bp.route('/wardrobe/<int:item_id>', methods=['DELETE'])
@jwt_required()
def delete_wardrobe_item(item_id):
    item = WardrobeItem.query.get(item_id)  # ❌ No user_id check!
    db.session.delete(item)  # User can delete anyone's items!
    db.session.commit()
    return jsonify({'success': True}), 200

# NEVER DO THIS - Using user_id from request body
@bp.route('/profile', methods=['GET'])
def get_profile():
    user_id = request.args.get('user_id')  # ❌ User can request any profile!
    user = User.query.get(user_id)
    return jsonify(user.to_dict()), 200
```

---

## 5. Rate Limiting

**Rule:** Implement rate limiting on authentication endpoints (5 requests per 15 minutes).

### ✅ Correct Implementation

```python
# Install Flask-Limiter
# pip install Flask-Limiter==3.5.0

# app/__init__.py
from flask_limiter import Limiter
from flask_limiter.util import get_remote_address

limiter = Limiter(
    app,
    key_func=get_remote_address,
    default_limits=["200 per day", "50 per hour"],
    storage_uri="memory://"
)

# app/routes/auth.py
from app import limiter

@bp.route('/login', methods=['POST'])
@limiter.limit("5 per 15 minutes")  # ✅ Prevent brute force
def login():
    # ... login logic
    pass

@bp.route('/register', methods=['POST'])
@limiter.limit("3 per hour")  # ✅ Prevent spam registration
def register():
    # ... registration logic
    pass

@bp.route('/password-reset', methods=['POST'])
@limiter.limit("3 per hour")  # ✅ Prevent abuse
def password_reset():
    # ... password reset logic
    pass
```

### ✅ Custom Rate Limit Error Response

```python
# app/__init__.py
from flask import jsonify

@app.errorhandler(429)
def ratelimit_handler(e):
    return jsonify({
        'success': False,
        'error': 'Too many requests. Please try again later.',
        'retry_after': e.description
    }), 429
```

---

## 6. SQL Injection Prevention

**Rule:** ALWAYS use SQLAlchemy ORM and parameterized queries. NEVER use string concatenation.

### ✅ Correct Implementation

```python
# Using ORM (Recommended)
user = User.query.filter_by(email=email).first()

# Using parameterized queries (if raw SQL needed)
from sqlalchemy import text

result = db.session.execute(
    text("SELECT * FROM users WHERE email = :email"),
    {"email": email}
)

# Filtering with multiple conditions
items = WardrobeItem.query.filter(
    WardrobeItem.user_id == user_id,
    WardrobeItem.category == category
).all()

# Search with LIKE (safe)
search_term = f"%{query}%"
users = User.query.filter(User.username.like(search_term)).all()
```

### ❌ Wrong Implementation (SQL Injection Vulnerability)

```python
# NEVER DO THIS - String concatenation
query = f"SELECT * FROM users WHERE email = '{email}'"  # ❌ VULNERABLE!
result = db.session.execute(query)

# NEVER DO THIS - String formatting
query = "SELECT * FROM users WHERE email = '%s'" % email  # ❌ VULNERABLE!

# NEVER DO THIS - Direct string in query
email = request.form['email']
query = "SELECT * FROM users WHERE email = '" + email + "'"  # ❌ VULNERABLE!
```

---

## 7. XSS Prevention

**Rule:** Sanitize all user inputs and escape outputs. Never render raw HTML from user input.

### ✅ Correct Implementation

```python
# Install bleach for HTML sanitization
# pip install bleach==6.1.0

import bleach

def sanitize_user_input(text, allow_tags=None):
    """
    Sanitize user input to prevent XSS
    """
    if allow_tags is None:
        allow_tags = []  # No HTML tags allowed by default
    
    # Clean HTML, removing dangerous tags and attributes
    clean_text = bleach.clean(
        text,
        tags=allow_tags,
        attributes={},
        strip=True
    )
    
    return clean_text

# Usage in routes
@bp.route('/profile', methods=['PUT'])
@jwt_required()
def update_profile():
    data = request.get_json()
    user_id = get_jwt_identity()
    
    user = User.query.get(user_id)
    
    # Sanitize bio before saving
    if 'bio' in data:
        user.bio = sanitize_user_input(data['bio'])
    
    db.session.commit()
    return jsonify({'success': True}), 200
```

### ✅ Frontend XSS Prevention (React)

```typescript
// React automatically escapes content, but be careful with dangerouslySetInnerHTML

// ✅ Safe - React escapes by default
<div>{user.bio}</div>

// ❌ NEVER DO THIS unless absolutely necessary and content is sanitized
<div dangerouslySetInnerHTML={{ __html: user.bio }} />

// ✅ If you must render HTML, sanitize on backend first
import DOMPurify from 'dompurify';

function SafeHTML({ html }: { html: string }) {
  const clean = DOMPurify.sanitize(html);
  return <div dangerouslySetInnerHTML={{ __html: clean }} />;
}
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - Storing unsanitized HTML
user.bio = request.form['bio']  # ❌ Could contain <script> tags

# NEVER DO THIS - Rendering raw user content
return f"<div>{user.bio}</div>"  # ❌ XSS vulnerability
```

---

## 8. CORS Configuration

**Rule:** Restrict CORS to your frontend domain only. Never use `*` in production.

### ✅ Correct Implementation

```python
# app/__init__.py
from flask_cors import CORS

def create_app():
    app = Flask(__name__)
    
    # Development: Allow localhost
    if app.config['ENV'] == 'development':
        CORS(app, origins=[
            'http://localhost:3000',
            'http://localhost:3001'
        ])
    # Production: Allow only frontend domain
    else:
        CORS(app, origins=[
            'https://stylesense.vercel.app',
            app.config.get('FRONTEND_URL')
        ])
    
    return app
```

### ✅ Advanced CORS Configuration

```python
from flask_cors import CORS

CORS(app,
    origins=['http://localhost:3000'],
    methods=['GET', 'POST', 'PUT', 'DELETE'],
    allow_headers=['Content-Type', 'Authorization'],
    supports_credentials=True,
    max_age=3600
)
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS in production
CORS(app, origins='*')  # ❌ Allows ANY domain!

# NEVER DO THIS
CORS(app)  # ❌ Defaults to allowing all origins
```

---

## 9. Environment Variables

**Rule:** NEVER commit secrets to git. Always use .env file and .env.example template.

### ✅ Correct Implementation

```bash
# .env (NEVER commit this file)
SECRET_KEY=your-super-secret-key-here-min-32-chars
JWT_SECRET_KEY=another-secret-key-for-jwt-tokens
DATABASE_URL=postgresql://user:password@localhost:5432/stylesense
OPENWEATHER_API_KEY=your-api-key-here
FIREBASE_CREDENTIALS_PATH=/path/to/credentials.json

# Production
FLASK_ENV=production
```

```bash
# .env.example (Commit this as template)
SECRET_KEY=change-me-in-production
JWT_SECRET_KEY=change-me-in-production
DATABASE_URL=sqlite:///stylesense.db
OPENWEATHER_API_KEY=your-api-key-here
FIREBASE_CREDENTIALS_PATH=path/to/serviceAccountKey.json

# Environment
FLASK_ENV=development
```

```python
# .gitignore (Must include)
.env
*.env
serviceAccountKey.json
*.log
__pycache__/
*.pyc
instance/
.pytest_cache/
venv/
env/
```

### ✅ Loading Environment Variables

```python
# config.py
import os
from dotenv import load_dotenv

load_dotenv()  # Load .env file

class Config:
    SECRET_KEY = os.getenv('SECRET_KEY')
    JWT_SECRET_KEY = os.getenv('JWT_SECRET_KEY')
    SQLALCHEMY_DATABASE_URI = os.getenv('DATABASE_URL')
    
    # Validate required env vars
    @staticmethod
    def validate():
        required = ['SECRET_KEY', 'JWT_SECRET_KEY', 'DATABASE_URL']
        missing = [var for var in required if not os.getenv(var)]
        
        if missing:
            raise ValueError(f"Missing required environment variables: {', '.join(missing)}")
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - Hardcoded secrets
SECRET_KEY = 'my-secret-key-123'  # ❌ Never hardcode!
JWT_SECRET_KEY = 'jwt-secret'  # ❌ Never hardcode!
DATABASE_URL = 'postgresql://admin:password123@localhost/db'  # ❌ No!

# NEVER DO THIS - Committing .env file
# (Check your .gitignore!)
```

---

## 10. Error Handling

**Rule:** Return generic error messages to clients, log detailed errors server-side.

### ✅ Correct Implementation

```python
# app/__init__.py
import logging
from flask import jsonify

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('app.log'),
        logging.StreamHandler()
    ]
)

logger = logging.getLogger(__name__)

# Global error handlers
@app.errorhandler(400)
def bad_request(error):
    logger.warning(f"Bad request: {error}")
    return jsonify({
        'success': False,
        'error': 'Invalid request'
    }), 400

@app.errorhandler(401)
def unauthorized(error):
    logger.warning(f"Unauthorized access attempt: {error}")
    return jsonify({
        'success': False,
        'error': 'Authentication required'
    }), 401

@app.errorhandler(403)
def forbidden(error):
    logger.warning(f"Forbidden access: {error}")
    return jsonify({
        'success': False,
        'error': 'Access denied'
    }), 403

@app.errorhandler(404)
def not_found(error):
    return jsonify({
        'success': False,
        'error': 'Resource not found'
    }), 404

@app.errorhandler(500)
def internal_error(error):
    logger.error(f"Internal server error: {error}", exc_info=True)
    return jsonify({
        'success': False,
        'error': 'An internal error occurred'  # ✅ Generic message
    }), 500

@app.errorhandler(Exception)
def handle_exception(error):
    logger.error(f"Unhandled exception: {error}", exc_info=True)
    return jsonify({
        'success': False,
        'error': 'An unexpected error occurred'  # ✅ Generic message
    }), 500
```

### ✅ Try-Catch in Routes

```python
@bp.route('/wardrobe', methods=['POST'])
@jwt_required()
def create_wardrobe_item():
    try:
        data = request.get_json()
        user_id = get_jwt_identity()
        
        # Validate input
        if not data or 'name' not in data:
            return jsonify({
                'success': False,
                'error': 'Invalid input'
            }), 400
        
        # Create item
        item = WardrobeItem(
            user_id=user_id,
            name=sanitize_text(data['name']),
            category=data.get('category', 'other')
        )
        
        db.session.add(item)
        db.session.commit()
        
        logger.info(f"User {user_id} created wardrobe item {item.id}")
        
        return jsonify({
            'success': True,
            'data': item.to_dict(),
            'message': 'Item created successfully'
        }), 201
        
    except Exception as e:
        db.session.rollback()
        logger.error(f"Error creating wardrobe item: {str(e)}", exc_info=True)
        return jsonify({
            'success': False,
            'error': 'Failed to create item'  # ✅ Generic message
        }), 500
```

### ❌ Wrong Implementation

```python
# NEVER DO THIS - Exposing internal errors to client
@bp.route('/user/<int:id>', methods=['GET'])
def get_user(id):
    try:
        user = User.query.get(id)
        return jsonify(user.to_dict()), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500  # ❌ Exposes internal details!

# NEVER DO THIS - No error handling
@bp.route('/create', methods=['POST'])
def create():
    data = request.get_json()
    item = Item(**data)  # ❌ Can crash if data is malformed
    db.session.add(item)
    db.session.commit()  # ❌ Can crash, no rollback

# NEVER DO THIS - No logging
except Exception as e:
    pass  # ❌ Silent failure, no way to debug
```

---

## Security Checklist for FYP Defense

Use this checklist to verify security implementation:

### Authentication & Authorization
- [ ] Passwords hashed with bcrypt (12+ rounds)
- [ ] JWT tokens properly configured (1hr access, 7 day refresh)
- [ ] All protected routes use `@jwt_required()` decorator
- [ ] Token refresh mechanism implemented

### Input Validation
- [ ] All endpoints validate required fields
- [ ] Email format validation implemented
- [ ] Password strength requirements enforced
- [ ] User input sanitized before storage

### Access Control
- [ ] IDOR prevention: Always check user_id matches resource owner
- [ ] Authorization checks on all CRUD operations
- [ ] User can only access/modify their own resources

### API Security
- [ ] Rate limiting on auth endpoints
- [ ] CORS restricted to frontend domain only
- [ ] SQL injection prevented (using ORM only)
- [ ] XSS prevention (input sanitization)

### Configuration
- [ ] No secrets in code (using environment variables)
- [ ] .env file in .gitignore
- [ ] .env.example provided as template
- [ ] Secure random secret keys generated

### Error Handling
- [ ] Generic error messages to client
- [ ] Detailed errors logged server-side
- [ ] Global error handlers implemented
- [ ] Try-catch blocks in critical operations

### Production Readiness
- [ ] HTTPS enforced in production
- [ ] Debug mode disabled in production
- [ ] Database connection pooling configured
- [ ] Logging system set up

---

## Common Security Questions for FYP Defense

**Q: Why did you use bcrypt instead of MD5 or SHA256?**  
A: Bcrypt is specifically designed for password hashing with built-in salting and configurable work factor. MD5 and SHA256 are fast hashing algorithms, making them vulnerable to brute force attacks.

**Q: How do you prevent brute force login attacks?**  
A: I implemented rate limiting (5 requests per 15 minutes) on the login endpoint using Flask-Limiter, and account lockout after multiple failed attempts.

**Q: What is IDOR and how did you prevent it?**  
A: IDOR (Insecure Direct Object Reference) is when users can access resources by manipulating IDs. I prevent it by always checking that the authenticated user's ID matches the resource owner's ID in every query.

**Q: How do you handle XSS attacks?**  
A: I sanitize all user inputs using bleach library before storing them, and React automatically escapes content when rendering. I never use `dangerouslySetInnerHTML` without sanitization.

**Q: Why separate access and refresh tokens?**  
A: Short-lived access tokens (1 hour) limit exposure if compromised. Refresh tokens (7 days) allow seamless re-authentication without repeatedly entering credentials.

**Q: How do you secure environment variables?**  
A: All secrets are in .env file which is git-ignored. I provide .env.example as a template, and validate required environment variables on startup.

---

**Last Updated:** November 2024  
**Version:** 1.0  
**Status:** Active - Reference for All Development
