import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link, useNavigate } from "react-router-dom";

const Home = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        // Check if the user is logged in
        axios.get('/check-auth').then((response) => {
            setIsLoggedIn(response.data.loggedIn);  // Set login status from backend
        }).catch((error) => {
            console.error('Error checking authentication:', error);
        });
    }, []);

    const handleLogout = async () => {
        try {
            // Optional: Inform the backend about the logout
            // const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const token = localStorage.getItem("token");
            if (token) {
                const response = await fetch("http://127.0.0.1:8000/logoutUser", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${token}`,
                        "X-CSRF-TOKEN": csrfToken,
                    },
                });
            }

            // Remove the token from localStorage
            localStorage.removeItem("authToken");

            // Optional: Clear other user-related data from localStorage/sessionStorage
            localStorage.removeItem("userDetails"); // Example for user details

            // Update state and redirect to the login page
            // setIsLoggedIn(false);
            navigate("/login");
        } catch (error) {
            console.error("Logout failed:", error);
            // Handle errors (e.g., network issues)
        }
    };


    return (
        <div className="container">
            <div className="header">
                <Link to="/products" className="link">Products</Link>
                {isLoggedIn ? (
                    <>
                        <button onClick={handleLogout} className="link logout-button">Logout</button>
                    </>
                ) : (
                    <>
                        <Link to="/login" className="link">Login</Link>
                        <Link to="/register" className="link">Register</Link>
                    </>
                )}
            </div>

            <h1 className="main-title">Welcome To Home Page</h1>
        </div>
    );
};

export default Home;
