import clsx from 'clsx';
import { useState } from 'react';
import { FaBars, FaTimes } from 'react-icons/fa'; // Hamburger and close icons
import { NavLink, useLocation } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import EmptyCart from '../assets/images/EmptyCart.png';

export const CategoriesNavbar = () => {

    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const { isCartOpen, setIsCartOpen, totalQuantity } = useCart();

    const location = useLocation();
    

    return (
        <div className="relative">
            {/* Desktop Nav */}
            <div className="hidden md:flex items-center gap-6">
                <nav className="flex gap-6 mx-4">
                    <NavLink
                        to="/all"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway cursor-pointer',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-500'
                            )
                        }
                        data-testid={location.pathname === '/all' ? 'active-category-link' : 'category-link'}
                    >
                        All
                    </NavLink>
                    <NavLink
                        to="/clothes"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway cursor-pointer',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-500'
                            )
                        }
                        data-testid={location.pathname === '/clothes' ? 'active-category-link' : 'category-link'}
                    >
                        Clothes
                    </NavLink>
                    <NavLink
                        to="/tech"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway cursor-pointer',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-500'
                            )
                        }
                        data-testid={location.pathname === '/tech' ? 'active-category-link' : 'category-link'}
                    >
                        Tech
                    </NavLink>
                </nav>

                <button
                    data-testid="cart-btn"
                    className="relative cursor-pointer"
                    onClick={() => setIsCartOpen(!isCartOpen)}
                >
                    <img src={EmptyCart} alt="Cart" width={20} height={20} className="mx-auto" />
                    {totalQuantity > 0 && (
                        <span className="absolute -top-1.5 -right-2 bg-black text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-center">
                            {totalQuantity}
                        </span>
                    )}
                </button>
            </div>

            {/* Mobile Hamburger */}
            <div className="flex items-center justify-between  md:hidden">
                <button onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)} className="p-2 cursor-pointer">
                    {isMobileMenuOpen ? <FaTimes size={17} /> : <FaBars size={17} />}
                </button>
            </div>

            {/* Mobile Menu */}
            {isMobileMenuOpen && (
                <div className="absolute top-full left-0 right-0 bg-white shadow-md z-50 flex flex-col w-fit gap-4 p-4 md:hidden">
                    <NavLink
                        to="/all"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-700'
                            )
                        }
                        onClick={() => setIsMobileMenuOpen(false)}
                        data-testid={location.pathname === '/all' ? 'active-category-link' : 'category-link'}
                    >
                        All
                    </NavLink>
                    <NavLink
                        to="/clothes"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-700'
                            )
                        }
                        onClick={() => setIsMobileMenuOpen(false)}
                        data-testid={location.pathname === '/clothes' ? 'active-category-link' : 'category-link'}
                    >
                        Clothes
                    </NavLink>
                    <NavLink
                        to="/tech"
                        className={({ isActive }) =>
                            clsx(
                                'text-base font-raleway',
                                isActive ? 'text-[#5ECE7B]' : 'text-gray-700'
                            )
                        }
                        onClick={() => setIsMobileMenuOpen(false)}
                        data-testid={location.pathname === '/tech' ? 'active-category-link' : 'category-link'}
                    >
                        Tech
                    </NavLink>
                    <button
                        data-testid="cart-btn"
                        className="relative cursor-pointer self-start"
                        onClick={() => {
                            setIsCartOpen(!isCartOpen);
                            setIsMobileMenuOpen(false);
                        }}
                    >
                        <img src={EmptyCart} alt="Cart" width={20} height={20} />
                        {totalQuantity > 0 && (
                            <span className="absolute -top-1.5 -right-2 bg-black text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-center">
                                {totalQuantity}
                            </span>
                        )}
                    </button>
                </div>
            )}
        </div>
    );
};
