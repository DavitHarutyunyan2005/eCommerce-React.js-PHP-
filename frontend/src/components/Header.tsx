import React, { useState, useRef, useEffect } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import BrandIcon from '../images/BrandIcon.png';
import EmptyCart from '../images/EmptyCart.png';
import clsx from 'clsx';

const Header: React.FC = () => {
    const [underlineStyle, setUnderlineStyle] = useState<{
        left: number;
        width: number;
    }>({ left: 0, width: 0 });
    
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
        <header className="bg-white flex justify-between items-center p-4">
            <nav className="relative flex gap-6 p-4 text-lg">
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
            <button>
                <img src={BrandIcon} alt="Logo" width={41} height={41} className="mx-auto" />
            </button>
            <button data-testid='cart-btn'>
                <img src={EmptyCart} alt="Cart" width={20} height={20} className="mx-auto" />
            </button>
        </header>
    );
};

export default Header;
