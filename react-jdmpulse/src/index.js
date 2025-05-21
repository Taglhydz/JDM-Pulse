import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import reportWebVitals from './reportWebVitals';
import { createBrowserRouter, RouterProvider, Navigate } from 'react-router-dom';
import Profile from './pages/Profile';
import Discover from './pages/Discover';
import Connection from './pages/Connection';
import Register from './pages/Register';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';

const router = createBrowserRouter([
  {
    path: '/',
    element: <Navigate to="/connection" />,
  },
  {
    path: '/connection',
    element: <Connection/>,
  },
  {
    path: '/register',
    element: <Register />,
  },
  {
    path: '/login',
    element : <Login />
  },
  {
    path: '/profile',
    element: <Profile/>,
  },
  {
    path: '/discover',
    element: <Discover/>,
  },
  {
    path: '/dashboard',
    element: <Dashboard/>,
  },
]);

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <RouterProvider router={router}/>
  </React.StrictMode>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();