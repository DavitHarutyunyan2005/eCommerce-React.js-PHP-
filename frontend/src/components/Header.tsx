import React, { useState, useRef, useEffect } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import BrandIcon from '../images/BrandIcon.png';
import EmptyCart from '../images/EmptyCart.png';
import clsx from 'clsx';
import { useCart } from '../context/CartContext';


const Header: React.FC = () => {
    const [underlineStyle, setUnderlineStyle] = useState<{
        left: number;
        width: number;
    }>({ left: 0, width: 0 });

    const { cart, isCartOpen, setIsCartOpen } = useCart();
    const navRefs = useRef<(HTMLAnchorElement | null)[]>([]);

    const updateUnderline = (index: number) => {
        const activeLink = navRefs.current[index];
        if (activeLink) {
            const { offsetLeft, offsetWidth } = activeLink;
            setUnderlineStyle({ left: offsetLeft, width: offsetWidth });
        }
    };

    const location = useLocation();

    useEffect(() => {
        requestAnimationFrame(() => {
            const activeIndex = navRefs.current.findIndex((ref) =>
                ref?.classList.contains('text-[#5ECE7B]')
            );
            if (activeIndex !== -1) {
                updateUnderline(activeIndex);
            }
        });
    }, [location.pathname]);


    return (
        <header className={clsx("bg-white flex justify-between",
            "items-center sticky top-0 z-50 px-14")}>
            {/* NAVBAR */}
            <nav className="flex gap-6 p-6 text-lg">
                <NavLink
                    to="/women"
                    className={({ isActive }) =>
                        clsx(isActive && 'text-[#5ECE7B]', 'text-base')
                    }
                    onClick={() => updateUnderline(0)}
                    ref={(el) => {
                        navRefs.current[0] = el;
                    }}
                    data-testid={location.pathname === '/women' ? 'active-category-link' : 'category-link'}
                >
                    WOMEN
                </NavLink>
                <NavLink
                    to="/men"
                    className={({ isActive }) =>
                        clsx(isActive && 'text-[#5ECE7B]', 'text-base')
                    }
                    onClick={() => updateUnderline(1)}
                    ref={(el) => {
                        navRefs.current[1] = el;
                    }}
                    data-testid={location.pathname === '/men' ? 'active-category-link' : 'category-link'}
                >
                    MEN
                </NavLink>
                <NavLink
                    to="/kids"
                    className={({ isActive }) =>
                        clsx(isActive && 'text-[#5ECE7B]', 'text-base')
                    }
                    onClick={() => updateUnderline(2)}
                    ref={(el) => {
                        navRefs.current[2] = el;
                    }}
                    data-testid={location.pathname === '/kids' ? 'active-category-link' : 'category-link'}
                >
                    KIDS
                </NavLink>

                <div
                    className="absolute bottom-0 h-[2px] bg-[#5ECE7B] transition-all duration-300 ease-in-out"
                    style={{
                        left: `${underlineStyle.left - 10}px`,
                        width: `${underlineStyle.width + 20}px`,
                    }}
                />
            </nav>
            {/* GREEN BUTTON */}
            <button>
                <img src={BrandIcon} alt="Logo" width={41} height={41} className="mx-auto" />
            </button>
            {/* CART BUTTON */}
            <button 
                data-testid="cart-btn" 
                className="relative cursor-pointer"
                onClick={() => setIsCartOpen(!isCartOpen)}
            >
                <img src={EmptyCart} alt="Cart" width={20} height={20} className="mx-auto" />
                {cart.length > 0 && (
                    <span className="absolute -top-1.5 -right-2 bg-black text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center text-center">
                        {cart.length}
                    </span>
                )}
            </button>
        </header>
    );
};

export default Header;
