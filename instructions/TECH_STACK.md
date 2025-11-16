# StyleSense.AI - Technology Stack

Complete technical stack documentation with exact versions, setup commands, and configuration details for FYP project.

## Table of Contents
- [Backend Stack](#backend-stack)
- [Frontend Stack](#frontend-stack)
- [Database Stack](#database-stack)
- [Authentication & Security](#authentication--security)
- [Machine Learning Stack](#machine-learning-stack)
- [Cloud & Storage](#cloud--storage)
- [External APIs](#external-apis)
- [Development Tools](#development-tools)
- [Deployment Platforms](#deployment-platforms)

---

## Backend Stack

### Flask 3.0 (Python Web Framework)

**Version:** Flask 3.0.0  
**Purpose:** Main backend framework for RESTful API

**Installation:**
```bash
# Create virtual environment
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate

# Install Flask
pip install Flask==3.0.0
```

**Basic Setup:**
```python
# app/__init__.py
from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from flask_jwt_extended import JWTManager
from flask_cors import CORS

db = SQLAlchemy()
jwt = JWTManager()

def create_app():
    app = Flask(__name__)
    app.config.from_object('config.Config')
    
    # Initialize extensions
    db.init_app(app)
    jwt.init_app(app)
    CORS(app, origins=[app.config['FRONTEND_URL']])
    
    # Register blueprints
    from app.routes import auth, user, outfit, wardrobe
    app.register_blueprint(auth.bp)
    app.register_blueprint(user.bp)
    app.register_blueprint(outfit.bp)
    app.register_blueprint(wardrobe.bp)
    
    return app
```

**Required Dependencies:**
```txt
# requirements.txt
Flask==3.0.0
Flask-SQLAlchemy==3.1.1
Flask-JWT-Extended==4.5.3
Flask-CORS==4.0.0
Flask-Migrate==4.0.5
python-dotenv==1.0.0
```

### SQLAlchemy (ORM)

**Version:** SQLAlchemy 2.0.23 (via Flask-SQLAlchemy 3.1.1)  
**Purpose:** Database ORM for models and queries

**Model Example:**
```python
# app/models/user.py
from app import db
from datetime import datetime
from werkzeug.security import generate_password_hash, check_password_hash

class User(db.Model):
    __tablename__ = 'users'
    
    id = db.Column(db.Integer, primary_key=True)
    email = db.Column(db.String(120), unique=True, nullable=False, index=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    password_hash = db.Column(db.String(255), nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    profile = db.relationship('UserProfile', backref='user', uselist=False)
    wardrobe_items = db.relationship('WardrobeItem', backref='user', lazy='dynamic')
    
    def set_password(self, password):
        self.password_hash = generate_password_hash(password, method='bcrypt')
    
    def check_password(self, password):
        return check_password_hash(self.password_hash, password)
    
    def to_dict(self):
        return {
            'id': self.id,
            'email': self.email,
            'username': self.username,
            'created_at': self.created_at.isoformat()
        }
```

**Database Migrations:**
```bash
# Initialize migrations
flask db init

# Create migration
flask db migrate -m "Initial migration"

# Apply migration
flask db upgrade
```

---

## Frontend Stack

### Next.js 14 with App Router

**Version:** Next.js 14.0.4  
**Purpose:** React framework for frontend application

**Installation:**
```bash
# Create Next.js app with TypeScript
npx create-next-app@14.0.4 frontend --typescript --tailwind --app --eslint

# Navigate to frontend
cd frontend

# Install dependencies
npm install
```

**Configuration (next.config.js):**
```javascript
/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['firebasestorage.googleapis.com'], // For Firebase images
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL,
  },
}

module.exports = nextConfig
```

### React 18

**Version:** React 18.2.0  
**Purpose:** UI library (comes with Next.js)

**Dependencies:**
```json
{
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "next": "14.0.4"
  }
}
```

### TypeScript

**Version:** TypeScript 5.3.3  
**Purpose:** Type safety and better developer experience

**Configuration (tsconfig.json):**
```json
{
  "compilerOptions": {
    "target": "ES2020",
    "lib": ["dom", "dom.iterable", "esnext"],
    "allowJs": true,
    "skipLibCheck": true,
    "strict": true,
    "forceConsistentCasingInFileNames": true,
    "noEmit": true,
    "esModuleInterop": true,
    "module": "esnext",
    "moduleResolution": "bundler",
    "resolveJsonModule": true,
    "isolatedModules": true,
    "jsx": "preserve",
    "incremental": true,
    "plugins": [
      {
        "name": "next"
      }
    ],
    "paths": {
      "@/*": ["./src/*"]
    }
  },
  "include": ["next-env.d.ts", "**/*.ts", "**/*.tsx", ".next/types/**/*.ts"],
  "exclude": ["node_modules"]
}
```

**Type Definitions Example:**
```typescript
// src/types/user.ts
export interface User {
  id: number;
  email: string;
  username: string;
  created_at: string;
}

export interface UserProfile {
  id: number;
  user_id: number;
  age?: number;
  gender?: 'male' | 'female' | 'other';
  body_type?: 'pear' | 'apple' | 'hourglass' | 'rectangle' | 'inverted_triangle';
  style_preferences?: string[];
}

export interface AuthResponse {
  success: boolean;
  data: {
    access_token: string;
    refresh_token: string;
    user: User;
  };
  message: string;
}
```

### Tailwind CSS 3.4

**Version:** Tailwind CSS 3.4.1  
**Purpose:** Utility-first CSS framework

**Installation:**
```bash
npm install -D tailwindcss@3.4.1 postcss autoprefixer
npx tailwindcss init -p
```

**Configuration (tailwind.config.ts):**
```typescript
import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
    './src/components/**/*.{js,ts,jsx,tsx,mdx}',
    './src/app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
        },
      },
    },
  },
  plugins: [],
}
export default config
```

### shadcn/ui Components

**Version:** Latest (component library)  
**Purpose:** Pre-built, accessible React components

**Installation:**
```bash
# Initialize shadcn/ui
npx shadcn-ui@latest init

# Add components as needed
npx shadcn-ui@latest add button
npx shadcn-ui@latest add card
npx shadcn-ui@latest add input
npx shadcn-ui@latest add dialog
npx shadcn-ui@latest add dropdown-menu
npx shadcn-ui@latest add avatar
npx shadcn-ui@latest add badge
npx shadcn-ui@latest add select
```

**Usage Example:**
```typescript
// src/components/LoginForm.tsx
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"

export function LoginForm() {
  return (
    <Card className="w-full max-w-md">
      <CardHeader>
        <CardTitle>Login to StyleSense</CardTitle>
      </CardHeader>
      <CardContent>
        <form className="space-y-4">
          <Input type="email" placeholder="Email" />
          <Input type="password" placeholder="Password" />
          <Button className="w-full">Sign In</Button>
        </form>
      </CardContent>
    </Card>
  )
}
```

---

## Database Stack

### PostgreSQL 15 (Production)

**Version:** PostgreSQL 15.x  
**Purpose:** Production database (via Supabase free tier)

**Supabase Setup:**
```bash
# Install Supabase CLI (optional for local dev)
npm install -g supabase

# Login to Supabase
supabase login

# Link to project
supabase link --project-ref your-project-ref
```

**Connection String Format:**
```
postgresql://postgres:[password]@db.[project-ref].supabase.co:5432/postgres
```

**Environment Variable:**
```bash
# .env
DATABASE_URL=postgresql://postgres:password@db.xxxxx.supabase.co:5432/postgres
```

### SQLite (Development)

**Version:** SQLite 3.x  
**Purpose:** Local development database

**Configuration:**
```python
# config.py
import os

class Config:
    # Use SQLite for development, PostgreSQL for production
    SQLALCHEMY_DATABASE_URI = os.getenv(
        'DATABASE_URL',
        'sqlite:///stylesense.db'
    )
    SQLALCHEMY_TRACK_MODIFICATIONS = False
```

**Development Setup:**
```bash
# No installation needed, comes with Python
# Database file will be created automatically
flask db upgrade
```

---

## Authentication & Security

### Flask-JWT-Extended

**Version:** Flask-JWT-Extended 4.5.3  
**Purpose:** JWT token management for API authentication

**Installation:**
```bash
pip install Flask-JWT-Extended==4.5.3
```

**Configuration:**
```python
# config.py
from datetime import timedelta

class Config:
    # JWT Configuration
    JWT_SECRET_KEY = os.getenv('JWT_SECRET_KEY', 'dev-secret-key-change-in-production')
    JWT_ACCESS_TOKEN_EXPIRES = timedelta(hours=1)
    JWT_REFRESH_TOKEN_EXPIRES = timedelta(days=7)
    JWT_TOKEN_LOCATION = ['headers']
    JWT_HEADER_NAME = 'Authorization'
    JWT_HEADER_TYPE = 'Bearer'
```

**Usage Example:**
```python
# app/routes/auth.py
from flask import Blueprint, request, jsonify
from flask_jwt_extended import create_access_token, create_refresh_token, jwt_required, get_jwt_identity

bp = Blueprint('auth', __name__, url_prefix='/api/auth')

@bp.route('/login', methods=['POST'])
def login():
    data = request.get_json()
    email = data.get('email')
    password = data.get('password')
    
    user = User.query.filter_by(email=email).first()
    if user and user.check_password(password):
        access_token = create_access_token(identity=user.id)
        refresh_token = create_refresh_token(identity=user.id)
        
        return jsonify({
            'success': True,
            'data': {
                'access_token': access_token,
                'refresh_token': refresh_token,
                'user': user.to_dict()
            },
            'message': 'Login successful'
        }), 200
    
    return jsonify({
        'success': False,
        'error': 'Invalid credentials'
    }), 401

@bp.route('/profile', methods=['GET'])
@jwt_required()
def get_profile():
    user_id = get_jwt_identity()
    user = User.query.get(user_id)
    
    return jsonify({
        'success': True,
        'data': user.to_dict()
    }), 200
```

### NextAuth.js

**Version:** NextAuth.js 4.24.5  
**Purpose:** Frontend authentication management

**Installation:**
```bash
npm install next-auth@4.24.5
```

**Configuration:**
```typescript
// src/app/api/auth/[...nextauth]/route.ts
import NextAuth from "next-auth"
import CredentialsProvider from "next-auth/providers/credentials"

const handler = NextAuth({
  providers: [
    CredentialsProvider({
      name: 'Credentials',
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" }
      },
      async authorize(credentials) {
        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/auth/login`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(credentials)
        })
        
        const data = await res.json()
        
        if (res.ok && data.success) {
          return {
            id: data.data.user.id,
            email: data.data.user.email,
            accessToken: data.data.access_token,
            refreshToken: data.data.refresh_token
          }
        }
        
        return null
      }
    })
  ],
  callbacks: {
    async jwt({ token, user }) {
      if (user) {
        token.accessToken = user.accessToken
        token.refreshToken = user.refreshToken
      }
      return token
    },
    async session({ session, token }) {
      session.accessToken = token.accessToken
      session.refreshToken = token.refreshToken
      return session
    }
  },
  pages: {
    signIn: '/auth/signin',
  }
})

export { handler as GET, handler as POST }
```

### Bcrypt (Password Hashing)

**Version:** Comes with Werkzeug  
**Purpose:** Secure password hashing

**Installation:**
```bash
# Included with Flask
pip install Werkzeug==3.0.1
```

**Usage:**
```python
from werkzeug.security import generate_password_hash, check_password_hash

# Hash password (12 rounds minimum for FYP security requirements)
password_hash = generate_password_hash('user_password', method='bcrypt')

# Verify password
is_valid = check_password_hash(password_hash, 'user_password')
```

---

## Machine Learning Stack

### Scikit-learn 1.3

**Version:** Scikit-learn 1.3.2  
**Purpose:** Machine learning algorithms for recommendations

**Installation:**
```bash
pip install scikit-learn==1.3.2
```

**Example Usage:**
```python
# ml/recommendation_engine.py
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np

class OutfitRecommender:
    def __init__(self):
        self.vectorizer = TfidfVectorizer()
        
    def train(self, outfit_descriptions):
        """Train the recommender with outfit descriptions"""
        self.tfidf_matrix = self.vectorizer.fit_transform(outfit_descriptions)
        
    def get_recommendations(self, user_preferences, n=5):
        """Get top N outfit recommendations based on user preferences"""
        user_vector = self.vectorizer.transform([user_preferences])
        similarities = cosine_similarity(user_vector, self.tfidf_matrix)
        top_indices = similarities[0].argsort()[-n:][::-1]
        return top_indices
```

### TensorFlow 2.14 (Optional)

**Version:** TensorFlow 2.14.0  
**Purpose:** Deep learning for advanced recommendations (optional for FYP)

**Installation:**
```bash
# CPU version (lighter, suitable for FYP)
pip install tensorflow==2.14.0

# Or GPU version if available
pip install tensorflow-gpu==2.14.0
```

**Additional ML Libraries:**
```bash
pip install pandas==2.1.4
pip install numpy==1.26.2
pip install matplotlib==3.8.2
pip install seaborn==0.13.0
```

---

## Cloud & Storage

### Firebase Storage

**Version:** Firebase Admin SDK 6.3.0  
**Purpose:** Image storage for wardrobe items and outfit photos

**Installation:**
```bash
# Backend (Python)
pip install firebase-admin==6.3.0

# Frontend (JavaScript)
npm install firebase@10.7.1
```

**Backend Setup:**
```python
# app/services/firebase.py
import firebase_admin
from firebase_admin import credentials, storage
import os

def initialize_firebase():
    cred = credentials.Certificate('path/to/serviceAccountKey.json')
    firebase_admin.initialize_app(cred, {
        'storageBucket': 'your-project.appspot.com'
    })

def upload_image(file, filename):
    bucket = storage.bucket()
    blob = bucket.blob(f'wardrobe/{filename}')
    blob.upload_from_file(file)
    blob.make_public()
    return blob.public_url
```

**Frontend Setup:**
```typescript
// src/lib/firebase.ts
import { initializeApp } from 'firebase/app';
import { getStorage } from 'firebase/storage';

const firebaseConfig = {
  apiKey: process.env.NEXT_PUBLIC_FIREBASE_API_KEY,
  authDomain: process.env.NEXT_PUBLIC_FIREBASE_AUTH_DOMAIN,
  projectId: process.env.NEXT_PUBLIC_FIREBASE_PROJECT_ID,
  storageBucket: process.env.NEXT_PUBLIC_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: process.env.NEXT_PUBLIC_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.NEXT_PUBLIC_FIREBASE_APP_ID
};

const app = initializeApp(firebaseConfig);
export const storage = getStorage(app);
```

---

## External APIs

### OpenWeatherMap API

**Version:** API v2.5 (free tier)  
**Purpose:** Real-time weather data for outfit recommendations

**Setup:**
```bash
# Sign up at https://openweathermap.org/api
# Get API key from dashboard
```

**Configuration:**
```python
# config.py
OPENWEATHER_API_KEY = os.getenv('OPENWEATHER_API_KEY')
OPENWEATHER_BASE_URL = 'https://api.openweathermap.org/data/2.5'
```

**Usage Example:**
```python
# app/services/weather.py
import requests
from flask import current_app

def get_weather(city):
    api_key = current_app.config['OPENWEATHER_API_KEY']
    url = f"{current_app.config['OPENWEATHER_BASE_URL']}/weather"
    
    params = {
        'q': city,
        'appid': api_key,
        'units': 'metric'
    }
    
    response = requests.get(url, params=params)
    if response.status_code == 200:
        data = response.json()
        return {
            'temperature': data['main']['temp'],
            'condition': data['weather'][0]['main'],
            'description': data['weather'][0]['description'],
            'humidity': data['main']['humidity']
        }
    return None
```

### Twitter API v2 (Optional)

**Version:** Twitter API v2 (free tier)  
**Purpose:** Fashion trend tracking from social media

**Installation:**
```bash
pip install tweepy==4.14.0
```

**Setup:**
```python
# app/services/trends.py
import tweepy

def initialize_twitter():
    client = tweepy.Client(
        bearer_token=os.getenv('TWITTER_BEARER_TOKEN')
    )
    return client

def get_fashion_trends():
    client = initialize_twitter()
    query = '#fashion -is:retweet'
    tweets = client.search_recent_tweets(
        query=query,
        max_results=10,
        tweet_fields=['created_at', 'public_metrics']
    )
    return tweets.data
```

---

## Development Tools

### Essential Dependencies

```txt
# requirements.txt (Backend)
Flask==3.0.0
Flask-SQLAlchemy==3.1.1
Flask-JWT-Extended==4.5.3
Flask-CORS==4.0.0
Flask-Migrate==4.0.5
python-dotenv==1.0.0
Werkzeug==3.0.1
requests==2.31.0
firebase-admin==6.3.0
scikit-learn==1.3.2
pandas==2.1.4
numpy==1.26.2

# Development
pytest==7.4.3
pytest-flask==1.3.0
black==23.12.1
flake8==7.0.0
```

```json
// package.json (Frontend)
{
  "dependencies": {
    "next": "14.0.4",
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "next-auth": "^4.24.5",
    "firebase": "^10.7.1",
    "axios": "^1.6.2",
    "tailwindcss": "^3.4.1",
    "typescript": "^5.3.3"
  },
  "devDependencies": {
    "@types/node": "^20",
    "@types/react": "^18",
    "@types/react-dom": "^18",
    "autoprefixer": "^10.0.1",
    "postcss": "^8",
    "eslint": "^8",
    "eslint-config-next": "14.0.4"
  }
}
```

### Code Quality Tools

```bash
# Python (Backend)
pip install black==23.12.1        # Code formatter
pip install flake8==7.0.0         # Linter
pip install pytest==7.4.3         # Testing framework

# Format code
black app/

# Lint code
flake8 app/

# Run tests
pytest
```

```bash
# JavaScript (Frontend)
npm install -D eslint prettier

# Lint code
npm run lint

# Format code
npm run format
```

---

## Deployment Platforms

### Railway (Backend Hosting)

**Purpose:** Backend API deployment  
**Free Tier:** $5 credit/month

**Setup:**
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Initialize project
railway init

# Deploy
railway up
```

**Configuration (railway.json):**
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "gunicorn run:app",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

**Required Files:**
```
# Procfile
web: gunicorn run:app

# runtime.txt
python-3.11
```

### Render (Alternative Backend)

**Purpose:** Alternative to Railway  
**Free Tier:** Available

**Setup:** Connect GitHub repo and configure via dashboard

### Vercel (Frontend Hosting)

**Purpose:** Next.js frontend deployment  
**Free Tier:** Generous limits

**Setup:**
```bash
# Install Vercel CLI
npm install -g vercel

# Login
vercel login

# Deploy
vercel

# Production deployment
vercel --prod
```

**Configuration (vercel.json):**
```json
{
  "buildCommand": "npm run build",
  "devCommand": "npm run dev",
  "installCommand": "npm install",
  "framework": "nextjs",
  "regions": ["iad1"]
}
```

---

## Environment Variables Reference

### Backend (.env)
```bash
# Flask
FLASK_APP=run.py
FLASK_ENV=development
SECRET_KEY=your-secret-key-here

# Database
DATABASE_URL=sqlite:///stylesense.db
# DATABASE_URL=postgresql://user:pass@host:5432/dbname  # Production

# JWT
JWT_SECRET_KEY=your-jwt-secret-key-here

# External APIs
OPENWEATHER_API_KEY=your-openweather-api-key
TWITTER_BEARER_TOKEN=your-twitter-bearer-token  # Optional

# Firebase
FIREBASE_CREDENTIALS_PATH=path/to/serviceAccountKey.json

# CORS
FRONTEND_URL=http://localhost:3000
```

### Frontend (.env.local)
```bash
# API
NEXT_PUBLIC_API_URL=http://localhost:5000

# NextAuth
NEXTAUTH_URL=http://localhost:3000
NEXTAUTH_SECRET=your-nextauth-secret-key

# Firebase
NEXT_PUBLIC_FIREBASE_API_KEY=your-firebase-api-key
NEXT_PUBLIC_FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
NEXT_PUBLIC_FIREBASE_PROJECT_ID=your-project-id
NEXT_PUBLIC_FIREBASE_STORAGE_BUCKET=your-project.appspot.com
NEXT_PUBLIC_FIREBASE_MESSAGING_SENDER_ID=your-sender-id
NEXT_PUBLIC_FIREBASE_APP_ID=your-app-id
```

---

## Quick Start Commands

### Backend Setup
```bash
# Navigate to backend
cd backend

# Create virtual environment
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt

# Setup database
flask db upgrade

# Run development server
flask run
```

### Frontend Setup
```bash
# Navigate to frontend
cd frontend

# Install dependencies
npm install

# Run development server
npm run dev
```

### Full Stack Development
```bash
# Terminal 1: Backend
cd backend && source venv/bin/activate && flask run

# Terminal 2: Frontend
cd frontend && npm run dev
```

---

**Last Updated:** November 2024  
**Version:** 1.0  
**Maintained By:** FYP Team
