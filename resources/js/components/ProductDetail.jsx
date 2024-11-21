import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useParams } from 'react-router-dom';

const ProductDetail = () => {
    const { id } = useParams(); // Get the product ID from the URL
    const [product, setProduct] = useState(null);
    const [isLoggedIn, setIsLoggedIn] = useState(false);  // State to check login status
    const [quantity, setQuantity] = useState(1);
    const [user, setUser] = useState(null);
    const [cart, setCart] = useState([]);
    const [userRating, setUserRating] = useState(0);
    const [userReview, setUserReview] = useState('');
    const [averageRating, setAverageRating] = useState(0);
    const [reviews, setReviews] = useState([]);

    useEffect(() => {
        // Check if user is logged in (could be implemented with your authentication logic)
        axios.get('/api/user').then((response) => {
            setUser(response.data);
        });

        axios.get('/cart').then((response) => {
            setCart(response.data);  // Assuming your cart data comes with product_id and quantity
        });
    }, []);

    useEffect(() => {
        // Fetch product details and ratings
        axios.get(`/rating/${id}`)
            .then((response) => {
                const data = response.data;
                setAverageRating(data.average_rating || 0);
                setUserRating(data.user_rating || 0);
                setUserReview(data.user_review || 0);
            })
            .catch((error) => {
                console.error('Error fetching product details:', error);
            });

        axios.get(`/product/${id}/reviews`)
            .then(response => {
                setAverageRating(response.data.averageRating);
                setReviews(response.data.reviews);
            })
            .catch(error => console.error('Error fetching reviews:', error));
    }, [id]);

    useEffect(() => {
        // Check if the user is logged in
        axios.get('/check-auth').then((response) => {
            setIsLoggedIn(response.data.loggedIn);  // Set login status from backend
        }).catch((error) => {
            console.error('Error checking authentication:', error);
        });
        // Fetch the product details from the Laravel API
        axios.get(`/getproduct/${id}`)
            .then((response) => {
                setProduct(response.data);
            })
            .catch((error) => {
                console.error('Error fetching product details:', error);
            });
    }, [id]);

    const handleReviewSubmit = (rating) => {
        setUserRating(rating);
        if (isLoggedIn) {
            axios.post('/review-product', {product_id: product.id, review: userReview })
                .then(() => {
                    // alert('Rating and review submitted successfully!');
                    axios.get(`/rating/${id}`)
                        .then((response) => {
                            const data = response.data;
                            setAverageRating(data.average_rating || 0);
                            setUserRating(data.user_rating || 0);
                            setUserReview(data.user_review || 0);
                        })
                        .catch((error) => {
                            console.error('Error fetching product details:', error);
                        });

                    axios.get(`/product/${id}/reviews`)
                        .then(response => {
                            setAverageRating(response.data.averageRating);
                            setReviews(response.data.reviews);
                        })
                        .catch(error => console.error('Error fetching reviews:', error));
                })
                .catch((error) => console.error('Error submitting rating and review:', error));
        } else {
            alert('You must be logged in to submit a rating and review.');
        }
    };

    const handleRatingSubmit = (rating) => {
        if (!isLoggedIn) {
            alert('You must be logged in to rate this product!');
            return;
        }

        axios.post('/rating/add', {
            product_id: product.id,
            rating,
        })
            .then((response) => {
                // alert(response.data.message);
                setUserRating(rating);
            })
            .catch((error) => {
                console.error('Error submitting rating:', error);
            });
    };

    const handleIncrease = () => {
        setQuantity(prevQuantity => prevQuantity + 1); // Increase quantity by 1
    };

    const handleDecrease = () => {
        if (quantity > 1) {
            setQuantity(prevQuantity => prevQuantity - 1); // Decrease quantity by 1 but ensure it's at least 1
        }
    };

    const handleAddToCart = () => {
        if (!isLoggedIn) {
            alert('You must be logged in to add to the cart!');
            return;
        }

        axios.post('/cart/add', {
            product_id: product.id,
            quantity,
        })
        .then((response) => {
            axios.get('/cart').then((response) => {
                setCart(response.data); // Update the cart state with the latest data
            }).catch((error) => {
                console.error('Error fetching updated cart:', error);
            });
        })
        .catch((error) => {
            console.error('Error adding product to cart:', error);
        });
    };

    if (!product) {
        return <div>Loading...</div>; // Show loading message while fetching the data
    }

    const cartProduct = cart.find(item => item.product_id);
    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartQuantity = totalQuantity ? totalQuantity : 0;

    return (
        <>
        {/* Cart Product Quantity Display */}
        <div style={{
            display: 'flex', 
            justifyContent: 'center', 
            alignItems: 'center', 
            padding: '10px 0', 
            borderBottom: '1px solid #ccc'
        }}>
            <span>Cart Quantity: {cartQuantity}</span>
        </div>

        <div className="product-detail-container container">
            <h1>{product.name}</h1>
            <div className="product-detail-row">
                {/* Product Image */}
                <div className="product-image">
                    {product.image ? (
                        <img
                            src={`/storage/${product.image}`}
                            alt={product.name}
                        />
                    ) : (
                        <div className="product-placeholder">No Image</div>
                    )}
                </div>

                {/* Product Details */}
                <div className="product-info">
                    <h2>{product.name}</h2>
                    <p>{product.description}</p>
                    <p className="price">${product.price}</p>

                    {/* Quantity Control */}
                    <div style={{ marginBottom: '10px' }}>
                        <button 
                            onClick={handleDecrease} 
                            style={{ padding: '5px 10px', fontSize: '16px' }}
                        >
                            -
                        </button>
                        <span style={{ margin: '0 10px', fontSize: '16px' }}>{quantity}</span>
                        <button 
                            onClick={handleIncrease} 
                            style={{ padding: '5px 10px', fontSize: '16px' }}
                        >
                            +
                        </button>
                    </div>

                    <button 
                        onClick={handleAddToCart} 
                        style={{
                            backgroundColor: '#4CAF50', 
                            color: 'white', 
                            padding: '10px 20px', 
                            border: 'none', 
                            cursor: 'pointer',
                            fontSize: '16px'
                        }}
                    >
                        Add to Cart
                    </button>

                    {/* Add Review Section */}
                    {isLoggedIn && (
                        <div className="add-review-section">
                            <h3>Leave a Review</h3>
                            <div className="rating">
                                {[1, 2, 3, 4, 5].map(star => (
                                    <span key={star} 
                                          className={`star-rating ${userRating >= star ? 'selected' : ''}`} 
                                          onClick={() => handleRatingSubmit(star)}>★</span>
                                ))}
                            </div>
                            <textarea
                                value={userReview}
                                onChange={(e) => setUserReview(e.target.value)}
                                placeholder="Write your review here"
                            />
                            <button onClick={() => handleReviewSubmit(userRating)}>Submit Review</button>
                        </div>
                    )}
                    
                </div>
            </div>
        </div>

        {/* Average Rating */}
        <div className="average-rating container">
            <span>Average Rating:</span>
            <div className="stars">
                {[1, 2, 3, 4, 5].map(star => (
                    <span key={star} className={`star-rating ${star <= averageRating ? 'selected' : ''}`}>★</span>
                ))}
            </div>
            <span>({averageRating} out of 5)</span>
        </div>
        
        {/* Display User Reviews */}
        <ul className="reviews-list container">
            {reviews.map((review) => (
                <li key={review.id} className="review-card">
                    <div className="review-header">
                        <span className="username">{review.user.name}</span>
                        <span className="review-date">{new Date(review.created_at).toLocaleDateString()}</span>
                    </div>
                    <div className="review-rating">
                        <span className="stars">
                            {[1, 2, 3, 4, 5].map(star => (
                                <span key={star} className={`star-rating ${review.rating >= star ? 'selected' : ''}`}>★</span>
                            ))}
                        </span>
                    </div>
                    <div className="review-text">
                        {review.ratingtext}
                    </div>
                </li>
            ))}
        </ul>
        </>
    );
};

export default ProductDetail;
