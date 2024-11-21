import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

const ProductList = () => {
    const [products, setProducts] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);
    const [cart, setCart] = useState([]);
    const [filter, setFilter] = useState('');
    const [sort, setSort] = useState('');
    const [isLoggedIn, setIsLoggedIn] = useState(false); // Check login status
    const [quantities, setQuantities] = useState({}); // Track quantities for products

    useEffect(() => {
        axios.get('/check-auth')
            .then((response) => setIsLoggedIn(response.data.loggedIn))
            .catch((error) => console.error('Error checking authentication:', error));

        axios.get('/getproducts')
            .then((response) => {
                setProducts(response.data);
                setFilteredProducts(response.data);
                // Initialize quantities for all products
                const initialQuantities = {};
                response.data.forEach((product) => {
                    initialQuantities[product.id] = 1; // Default quantity = 1
                });
                setQuantities(initialQuantities);
            })
            .catch((error) => console.error('Error fetching products:', error));

        axios.get('/cart')
            .then((response) => setCart(response.data))
            .catch((error) => console.error('Error fetching cart:', error));
    }, []);

    const handleFilterChange = (e) => {
        const searchValue = e.target.value.toLowerCase();
        setFilter(searchValue);
        if (!searchValue) {
            setFilteredProducts(products);
            return;
        }
        const filtered = products.filter((product) => {
            const name = product.name?.toLowerCase() || '';
            return name.includes(searchValue);
        });
        setFilteredProducts(filtered);
    };

    const handleSortChange = (e) => {
        setSort(e.target.value);
        let sortedProducts = [...filteredProducts];
        if (e.target.value === 'price_asc') {
            sortedProducts.sort((a, b) => a.price - b.price);
        } else if (e.target.value === 'price_desc') {
            sortedProducts.sort((a, b) => b.price - a.price);
        }
        setFilteredProducts(sortedProducts);
    };

    const handleIncrease = (productId) => {
        setQuantities((prev) => ({
            ...prev,
            [productId]: prev[productId] + 1,
        }));
    };

    const handleDecrease = (productId) => {
        setQuantities((prev) => ({
            ...prev,
            [productId]: Math.max(prev[productId] - 1, 1),
        }));
    };

    const handleAddToCart = (productId) => {
        if (!isLoggedIn) {
            alert('You must be logged in to add to the cart!');
            return;
        }
        const quantity = quantities[productId];
        axios.post('/cart/add', { product_id: productId, quantity })
            .then(() => {
                axios.get('/cart')
                    .then((response) => setCart(response.data))
                    .catch((error) => console.error('Error fetching updated cart:', error));
            })
            .catch((error) => console.error('Error adding product to cart:', error));

        axios.get('/getproducts')
            .then((response) => {
                setProducts(response.data);
                setFilteredProducts(response.data);
                // Initialize quantities for all products
                const initialQuantities = {};
                response.data.forEach((product) => {
                    initialQuantities[product.id] = 1; // Default quantity = 1
                });
                setQuantities(initialQuantities);
            })
            .catch((error) => console.error('Error fetching products:', error));

        axios.get('/cart')
            .then((response) => setCart(response.data))
            .catch((error) => console.error('Error fetching cart:', error));
    };

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
        <div className="container">
            <h1>Product Listing</h1>
            <div className="controls">
                <input
                    type="text"
                    placeholder="Search by name"
                    value={filter}
                    onChange={handleFilterChange}
                    className="filter-input"
                />
                <select value={sort} onChange={handleSortChange} className="sort-select">
                    <option value="">Sort By</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating">Rating</option>
                </select>
            </div>
            <div className="product-list">
                {filteredProducts.map((product) => (
                    <div key={product.id} className="product-card">
                        <Link to={`/product/${product.id}`}>
                            <div className="product-image">
                                {product.image ? (
                                    <img
                                        src={`/storage/${product.image}`}
                                        alt={product.name}
                                        style={{ width: '100%', height: '150px', objectFit: 'cover', borderRadius: '5px' }}
                                    />
                                ) : (
                                    <div className="product-placeholder" style={{ width: '100%', height: '150px', objectFit: 'cover', borderRadius: '5px' }}>No Image</div>
                                )}
                            </div>
                            <h2>{product.name}</h2>
                        </Link>
                        <p>{product.description}</p>
                        <p><strong>Price:</strong> ${product.price}</p>

                        {/* Quantity Controls */}
                        <div style={{ marginBottom: '10px' }}>
                            <button
                                onClick={() => handleDecrease(product.id)}
                                style={{ padding: '5px 10px', fontSize: '16px' }}
                            >
                                -
                            </button>
                            <span style={{ margin: '0 10px', fontSize: '16px' }}>{quantities[product.id]}</span>
                            <button
                                onClick={() => handleIncrease(product.id)}
                                style={{ padding: '5px 10px', fontSize: '16px' }}
                            >
                                +
                            </button>
                        </div>

                        {/* Add to Cart Button */}
                        <button
                            onClick={() => handleAddToCart(product.id)}
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
                    </div>
                ))}
            </div>
        </div>
        </>
    );
};

export default ProductList;
