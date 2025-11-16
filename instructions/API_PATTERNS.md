# StyleSense.AI - API Patterns & Standards

**PURPOSE:** Consistent API design across all endpoints for professional FYP demonstration.

## Table of Contents
- [Response Format](#response-format)
- [HTTP Status Codes](#http-status-codes)
- [Authentication Header](#authentication-header)
- [Pagination Pattern](#pagination-pattern)
- [Timestamp Format](#timestamp-format)
- [Protected Endpoint Pattern](#protected-endpoint-pattern)
- [Request/Response Examples](#requestresponse-examples)
- [Error Response Format](#error-response-format)
- [API Documentation](#api-documentation)

---

## Response Format

**RULE:** ALL API responses must follow this exact JSON structure.

### Standard Success Response

```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "username": "johndoe"
    }
  },
  "message": "Operation completed successfully"
}
```

### Standard Error Response

```json
{
  "success": false,
  "error": "Error description here",
  "message": "User-friendly error message"
}
```

### Implementation

```python
# app/utils/responses.py

def success_response(data=None, message="Success", status_code=200):
    """
    Standard success response format
    
    Args:
        data: Response data (dict, list, or None)
        message: Success message
        status_code: HTTP status code (default 200)
    
    Returns:
        tuple: (response_dict, status_code)
    """
    response = {
        'success': True,
        'message': message
    }
    
    if data is not None:
        response['data'] = data
    
    return jsonify(response), status_code


def error_response(error, message=None, status_code=400):
    """
    Standard error response format
    
    Args:
        error: Error description
        message: Optional user-friendly message
        status_code: HTTP status code (default 400)
    
    Returns:
        tuple: (response_dict, status_code)
    """
    response = {
        'success': False,
        'error': error
    }
    
    if message:
        response['message'] = message
    
    return jsonify(response), status_code
```

### Usage in Routes

```python
from app.utils.responses import success_response, error_response

@bp.route('/users/<int:user_id>', methods=['GET'])
@jwt_required()
def get_user(user_id):
    user = User.query.get(user_id)
    
    if not user:
        return error_response(
            error='User not found',
            status_code=404
        )
    
    return success_response(
        data={'user': user.to_dict()},
        message='User retrieved successfully'
    )
```

---

## HTTP Status Codes

**RULE:** Use appropriate HTTP status codes for all responses.

### Status Code Reference

| Code | Meaning | When to Use | Example |
|------|---------|-------------|---------|
| **200** | OK | Successful GET, PUT, PATCH | User profile retrieved |
| **201** | Created | Successful POST (resource created) | New wardrobe item created |
| **204** | No Content | Successful DELETE | Item deleted successfully |
| **400** | Bad Request | Validation error, invalid input | Missing required fields |
| **401** | Unauthorized | Authentication required/failed | Invalid credentials |
| **403** | Forbidden | Authenticated but no permission | Accessing another user's resource |
| **404** | Not Found | Resource doesn't exist | User/item not found |
| **409** | Conflict | Resource already exists | Email already registered |
| **422** | Unprocessable Entity | Validation failed | Invalid email format |
| **429** | Too Many Requests | Rate limit exceeded | Too many login attempts |
| **500** | Internal Server Error | Unexpected server error | Database connection failed |

### Examples

```python
# 200 OK - Successful retrieval
@bp.route('/profile', methods=['GET'])
@jwt_required()
def get_profile():
    user_id = get_jwt_identity()
    user = User.query.get(user_id)
    return success_response(data={'user': user.to_dict()}), 200

# 201 Created - Resource created
@bp.route('/wardrobe', methods=['POST'])
@jwt_required()
def create_item():
    # ... create item
    return success_response(
        data={'item': item.to_dict()},
        message='Item created successfully'
    ), 201

# 204 No Content - Successful deletion
@bp.route('/wardrobe/<int:id>', methods=['DELETE'])
@jwt_required()
def delete_item(id):
    # ... delete item
    return '', 204

# 400 Bad Request - Validation error
@bp.route('/register', methods=['POST'])
def register():
    data = request.get_json()
    if not data or 'email' not in data:
        return error_response(
            error='Email is required',
            status_code=400
        )

# 401 Unauthorized - Authentication failed
@bp.route('/login', methods=['POST'])
def login():
    # ... check credentials
    if not valid:
        return error_response(
            error='Invalid credentials',
            status_code=401
        )

# 403 Forbidden - No permission
@bp.route('/admin/users', methods=['GET'])
@jwt_required()
def get_all_users():
    if not current_user.is_admin:
        return error_response(
            error='Admin access required',
            status_code=403
        )

# 404 Not Found - Resource not found
@bp.route('/users/<int:id>', methods=['GET'])
def get_user(id):
    user = User.query.get(id)
    if not user:
        return error_response(
            error='User not found',
            status_code=404
        )

# 409 Conflict - Resource already exists
@bp.route('/register', methods=['POST'])
def register():
    if User.query.filter_by(email=email).first():
        return error_response(
            error='Email already registered',
            status_code=409
        )

# 500 Internal Server Error - Unexpected error
@bp.route('/something', methods=['POST'])
def something():
    try:
        # ... operation
        pass
    except Exception as e:
        logger.error(f"Unexpected error: {e}")
        return error_response(
            error='An internal error occurred',
            status_code=500
        )
```

---

## Authentication Header

**RULE:** All protected endpoints require Bearer token in Authorization header.

### Header Format

```
Authorization: Bearer <access_token>
```

### Frontend Implementation (JavaScript/TypeScript)

```typescript
// src/lib/api.ts
const API_URL = process.env.NEXT_PUBLIC_API_URL;

async function apiRequest(
  endpoint: string,
  options: RequestInit = {}
): Promise<any> {
  // Get token from NextAuth session or localStorage
  const token = await getAccessToken();
  
  const headers = {
    'Content-Type': 'application/json',
    ...(token && { 'Authorization': `Bearer ${token}` }),
    ...options.headers,
  };
  
  const response = await fetch(`${API_URL}${endpoint}`, {
    ...options,
    headers,
  });
  
  const data = await response.json();
  
  if (!response.ok) {
    throw new Error(data.error || 'An error occurred');
  }
  
  return data;
}

// Usage examples
export const getProfile = () => apiRequest('/api/user/profile');

export const createWardrobeItem = (itemData: any) =>
  apiRequest('/api/wardrobe', {
    method: 'POST',
    body: JSON.stringify(itemData),
  });
```

### Backend Verification

```python
from flask_jwt_extended import jwt_required, get_jwt_identity

@bp.route('/protected', methods=['GET'])
@jwt_required()  # ✅ Automatically validates token from Authorization header
def protected_route():
    user_id = get_jwt_identity()
    # ... rest of the logic
```

---

## Pagination Pattern

**RULE:** All list endpoints must support pagination with consistent parameters.

### Pagination Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | int | 1 | Current page number (1-indexed) |
| `per_page` | int | 20 | Items per page (max 100) |

### Pagination Response

```json
{
  "success": true,
  "data": {
    "items": [...],
    "pagination": {
      "page": 1,
      "per_page": 20,
      "total": 156,
      "pages": 8,
      "has_next": true,
      "has_prev": false
    }
  },
  "message": "Items retrieved successfully"
}
```

### Implementation

```python
# app/utils/pagination.py

def paginate_query(query, page=1, per_page=20, max_per_page=100):
    """
    Paginate a SQLAlchemy query
    
    Args:
        query: SQLAlchemy query object
        page: Current page number (default 1)
        per_page: Items per page (default 20)
        max_per_page: Maximum items per page (default 100)
    
    Returns:
        dict: Paginated response with items and pagination metadata
    """
    # Validate parameters
    page = max(1, int(page))
    per_page = min(max(1, int(per_page)), max_per_page)
    
    # Get paginated results
    pagination = query.paginate(
        page=page,
        per_page=per_page,
        error_out=False
    )
    
    return {
        'items': [item.to_dict() for item in pagination.items],
        'pagination': {
            'page': pagination.page,
            'per_page': pagination.per_page,
            'total': pagination.total,
            'pages': pagination.pages,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev
        }
    }
```

### Usage Example

```python
from app.utils.pagination import paginate_query

@bp.route('/wardrobe', methods=['GET'])
@jwt_required()
def get_wardrobe_items():
    user_id = get_jwt_identity()
    
    # Get query parameters
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 20, type=int)
    
    # Build query
    query = WardrobeItem.query.filter_by(user_id=user_id)
    
    # Apply pagination
    result = paginate_query(query, page=page, per_page=per_page)
    
    return success_response(data=result), 200
```

### Frontend Usage

```typescript
// Fetch paginated data
const fetchWardrobeItems = async (page: number = 1, perPage: number = 20) => {
  const response = await apiRequest(
    `/api/wardrobe?page=${page}&per_page=${perPage}`
  );
  return response.data;
};

// React component example
function WardrobeList() {
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  
  useEffect(() => {
    fetchWardrobeItems(currentPage).then(data => {
      setItems(data.items);
      setPagination(data.pagination);
    });
  }, [currentPage]);
  
  return (
    <div>
      {items.map(item => <ItemCard key={item.id} item={item} />)}
      
      <Pagination
        current={pagination?.page}
        total={pagination?.pages}
        onChange={setCurrentPage}
      />
    </div>
  );
}
```

---

## Timestamp Format

**RULE:** All timestamps must be in ISO 8601 format (UTC).

### Format: `YYYY-MM-DDTHH:MM:SS.sssZ`

Example: `2024-11-16T08:54:49.940Z`

### Backend Implementation

```python
from datetime import datetime

class BaseModel(db.Model):
    __abstract__ = True
    
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(
        db.DateTime,
        default=datetime.utcnow,
        onupdate=datetime.utcnow
    )
    
    def to_dict(self):
        return {
            'id': self.id,
            'created_at': self.created_at.isoformat() + 'Z',  # ✅ ISO format
            'updated_at': self.updated_at.isoformat() + 'Z'
        }
```

### Frontend Usage

```typescript
// Parse ISO timestamp
const createdDate = new Date(item.created_at);

// Format for display
const formatDate = (isoString: string) => {
  return new Date(isoString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Usage
<p>Created: {formatDate(item.created_at)}</p>
```

---

## Protected Endpoint Pattern

**RULE:** Use consistent pattern for protected routes with ownership verification.

### Standard Protected Route Template

```python
@bp.route('/resource/<int:resource_id>', methods=['GET'])
@jwt_required()
def get_resource(resource_id):
    """
    Standard pattern for protected endpoint:
    1. Get authenticated user ID
    2. Query resource with ownership check
    3. Return 404 if not found or not owned
    4. Return resource data
    """
    # Step 1: Get authenticated user
    user_id = get_jwt_identity()
    
    # Step 2: Query with ownership check
    resource = Resource.query.filter_by(
        id=resource_id,
        user_id=user_id  # ✅ Ownership verification
    ).first()
    
    # Step 3: Handle not found
    if not resource:
        return error_response(
            error='Resource not found or access denied',
            status_code=404
        )
    
    # Step 4: Return data
    return success_response(
        data={'resource': resource.to_dict()}
    ), 200
```

### CRUD Pattern Examples

```python
# CREATE
@bp.route('/wardrobe', methods=['POST'])
@jwt_required()
def create_item():
    user_id = get_jwt_identity()
    data = request.get_json()
    
    # Validate input
    if not data or 'name' not in data:
        return error_response('Name is required', status_code=400)
    
    # Create resource
    item = WardrobeItem(
        user_id=user_id,  # ✅ Associate with user
        name=data['name'],
        category=data.get('category')
    )
    
    db.session.add(item)
    db.session.commit()
    
    return success_response(
        data={'item': item.to_dict()},
        message='Item created successfully'
    ), 201

# READ (Single)
@bp.route('/wardrobe/<int:item_id>', methods=['GET'])
@jwt_required()
def get_item(item_id):
    user_id = get_jwt_identity()
    
    item = WardrobeItem.query.filter_by(
        id=item_id,
        user_id=user_id  # ✅ Ownership check
    ).first()
    
    if not item:
        return error_response('Item not found', status_code=404)
    
    return success_response(data={'item': item.to_dict()}), 200

# READ (List with pagination)
@bp.route('/wardrobe', methods=['GET'])
@jwt_required()
def get_items():
    user_id = get_jwt_identity()
    
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 20, type=int)
    
    query = WardrobeItem.query.filter_by(user_id=user_id)
    result = paginate_query(query, page, per_page)
    
    return success_response(data=result), 200

# UPDATE
@bp.route('/wardrobe/<int:item_id>', methods=['PUT'])
@jwt_required()
def update_item(item_id):
    user_id = get_jwt_identity()
    data = request.get_json()
    
    item = WardrobeItem.query.filter_by(
        id=item_id,
        user_id=user_id  # ✅ Ownership check
    ).first()
    
    if not item:
        return error_response('Item not found', status_code=404)
    
    # Update fields
    if 'name' in data:
        item.name = data['name']
    if 'category' in data:
        item.category = data['category']
    
    db.session.commit()
    
    return success_response(
        data={'item': item.to_dict()},
        message='Item updated successfully'
    ), 200

# DELETE
@bp.route('/wardrobe/<int:item_id>', methods=['DELETE'])
@jwt_required()
def delete_item(item_id):
    user_id = get_jwt_identity()
    
    item = WardrobeItem.query.filter_by(
        id=item_id,
        user_id=user_id  # ✅ Ownership check
    ).first()
    
    if not item:
        return error_response('Item not found', status_code=404)
    
    db.session.delete(item)
    db.session.commit()
    
    return success_response(
        message='Item deleted successfully'
    ), 200
```

---

## Request/Response Examples

### Authentication Endpoints

#### POST /api/auth/register

**Request:**
```json
{
  "email": "user@example.com",
  "username": "johndoe",
  "password": "SecurePass123!"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "username": "johndoe",
      "created_at": "2024-11-16T08:54:49.940Z"
    }
  },
  "message": "Registration successful"
}
```

#### POST /api/auth/login

**Request:**
```json
{
  "email": "user@example.com",
  "password": "SecurePass123!"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "refresh_token": "eyJhbGciOiJIUzI1NiIs...",
    "user": {
      "id": 1,
      "email": "user@example.com",
      "username": "johndoe"
    }
  },
  "message": "Login successful"
}
```

### Wardrobe Endpoints

#### GET /api/wardrobe?page=1&per_page=20

**Request Headers:**
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "user_id": 1,
        "name": "Blue Denim Jeans",
        "category": "pants",
        "color": "blue",
        "image_url": "https://...",
        "created_at": "2024-11-16T08:54:49.940Z",
        "updated_at": "2024-11-16T08:54:49.940Z"
      }
    ],
    "pagination": {
      "page": 1,
      "per_page": 20,
      "total": 45,
      "pages": 3,
      "has_next": true,
      "has_prev": false
    }
  },
  "message": "Items retrieved successfully"
}
```

#### POST /api/wardrobe

**Request Headers:**
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Red Cotton T-Shirt",
  "category": "tops",
  "color": "red",
  "brand": "Nike",
  "size": "M"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "item": {
      "id": 46,
      "user_id": 1,
      "name": "Red Cotton T-Shirt",
      "category": "tops",
      "color": "red",
      "brand": "Nike",
      "size": "M",
      "created_at": "2024-11-16T09:00:00.000Z",
      "updated_at": "2024-11-16T09:00:00.000Z"
    }
  },
  "message": "Item created successfully"
}
```

### Outfit Recommendations

#### GET /api/recommendations?occasion=casual&weather=sunny

**Request Headers:**
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "recommendations": [
      {
        "id": 1,
        "outfit_items": [
          {
            "id": 1,
            "name": "Blue Denim Jeans",
            "category": "pants"
          },
          {
            "id": 5,
            "name": "White T-Shirt",
            "category": "tops"
          }
        ],
        "score": 0.95,
        "reason": "Perfect for sunny casual occasions"
      }
    ],
    "weather": {
      "temperature": 25,
      "condition": "Sunny"
    }
  },
  "message": "Recommendations generated successfully"
}
```

---

## Error Response Format

### Validation Error (400)
```json
{
  "success": false,
  "error": "Validation failed",
  "message": "Email is required",
  "details": {
    "email": ["This field is required"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

### Authentication Error (401)
```json
{
  "success": false,
  "error": "Invalid credentials",
  "message": "Email or password is incorrect"
}
```

### Authorization Error (403)
```json
{
  "success": false,
  "error": "Access denied",
  "message": "You don't have permission to access this resource"
}
```

### Not Found (404)
```json
{
  "success": false,
  "error": "Resource not found",
  "message": "The requested item does not exist"
}
```

### Conflict (409)
```json
{
  "success": false,
  "error": "Email already exists",
  "message": "An account with this email already exists"
}
```

### Rate Limit (429)
```json
{
  "success": false,
  "error": "Too many requests",
  "message": "Please try again in 15 minutes",
  "retry_after": 900
}
```

### Server Error (500)
```json
{
  "success": false,
  "error": "Internal server error",
  "message": "An unexpected error occurred. Please try again later"
}
```

---

## API Documentation

### Swagger/OpenAPI Setup

```python
# Install Flask-RESTX for API documentation
# pip install flask-restx==1.3.0

from flask_restx import Api, Resource, fields

api = Api(
    app,
    version='1.0',
    title='StyleSense.AI API',
    description='AI-Powered Fashion Recommendation API',
    doc='/api/docs'
)

# Define namespaces
auth_ns = api.namespace('auth', description='Authentication operations')
wardrobe_ns = api.namespace('wardrobe', description='Wardrobe management')

# Define models
user_model = api.model('User', {
    'id': fields.Integer(description='User ID'),
    'email': fields.String(required=True, description='Email address'),
    'username': fields.String(required=True, description='Username'),
    'created_at': fields.DateTime(description='Creation timestamp')
})

# Document endpoints
@auth_ns.route('/login')
class Login(Resource):
    @api.doc('login')
    @api.expect(login_model)
    @api.response(200, 'Success', auth_response_model)
    @api.response(401, 'Invalid credentials')
    def post(self):
        """Login user and return JWT tokens"""
        pass
```

---

## Quick Reference Cheat Sheet

```python
# ✅ Standard Response Pattern
return success_response(data={'item': item.to_dict()}), 200
return error_response('Item not found', status_code=404)

# ✅ Protected Route Pattern
@bp.route('/resource/<int:id>', methods=['GET'])
@jwt_required()
def get_resource(id):
    user_id = get_jwt_identity()
    resource = Resource.query.filter_by(id=id, user_id=user_id).first()
    if not resource:
        return error_response('Not found', status_code=404)
    return success_response(data={'resource': resource.to_dict()}), 200

# ✅ Pagination Pattern
page = request.args.get('page', 1, type=int)
per_page = request.args.get('per_page', 20, type=int)
result = paginate_query(query, page, per_page)
return success_response(data=result), 200

# ✅ Timestamp Format
created_at = datetime.utcnow()
timestamp_str = created_at.isoformat() + 'Z'

# ✅ Authentication Header (Frontend)
headers = {
    'Authorization': `Bearer ${accessToken}`,
    'Content-Type': 'application/json'
}
```

---

**Last Updated:** November 2024  
**Version:** 1.0  
**Status:** Active - Reference for All API Development
