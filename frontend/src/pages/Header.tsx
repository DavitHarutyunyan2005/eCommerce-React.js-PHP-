import React, { useState, useRef, useEffect } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import BrandIcon from '../assets/images/BrandIcon.png';
import clsx from 'clsx';
import { CategoriesNavbar } from '../components/CategoriesNavbar';

const Header: React.FC = () => {
    const [underlineStyle, setUnderlineStyle] = useState<{
        left: number;
        width: number;
    }>({ left: 0, width: 0 });

    // const { isCartOpen, setIsCartOpen, totalQuantity } = useCart();
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

    const isClothesPage = location.pathname.startsWith('/clothes');

    return (
        <header className={clsx("bg-white flex justify-between items-center",
            "sticky top-0 z-50 px-14 p-4")}>

            {/* CLOTHES NAVBAR */}
            {isClothesPage &&
                <nav className="flex justify-around  items-center gap-6 text-lg">
                    <NavLink
                        to="/clothes/women"
                        className={({ isActive }) =>
                            clsx(isActive && 'text-[#5ECE7B]', 'text-base font-raleway')
                        }
                        onClick={() => updateUnderline(0)}
                        ref={(el) => {
                            navRefs.current[0] = el;
                        }}
                    >
                        WOMEN
                    </NavLink>
                    <NavLink
                        to="/clothes/men"
                        className={({ isActive }) =>
                            clsx(isActive && 'text-[#5ECE7B]', 'text-base font-raleway')
                        }
                        onClick={() => updateUnderline(1)}
                        ref={(el) => {
                            navRefs.current[1] = el;
                        }}
                    >
                        MEN
                    </NavLink>
                    <NavLink
                        to="/clothes/kids"
                        className={({ isActive }) =>
                            clsx(isActive && 'text-[#5ECE7B]', 'text-base font-raleway')
                        }
                        onClick={() => updateUnderline(2)}
                        ref={(el) => {
                            navRefs.current[2] = el;
                        }}
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
            }

            {/* Logo */}
            <div>
                <img src={BrandIcon} alt="Logo" width={41} height={41} className="mx-auto" />
            </div>

            {/* Categories Navbar */}
            <CategoriesNavbar/>

        </header>
    );
};

export default Header;
