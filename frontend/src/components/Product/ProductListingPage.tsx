import React from 'react';
import { useQuery } from '@apollo/client';
import { GET_PRODUCTS } from '../../graphql/queries/GET_PRODUCTS';
import ProductCard from './ProductCard';
import ProductCardSkeleton from './ProductCardSkeleton';
import { useCart } from '../../context/CartContext';
import clsx from 'clsx';
import { CartOverlay } from '../CartOverlay/CartOverlay';

interface ProductListingPageProps {
    category: string;
    madeFor: 'Men' | 'Women' | 'Kids';
}

const ProductListingPage: React.FC<ProductListingPageProps> = ({ category, madeFor }) => {
    const { data, loading, error, } = useQuery(GET_PRODUCTS);
    const { isCartOpen, setIsCartOpen } = useCart();

    const products = (data?.products ?? []).filter(
        (product: any) => product.category === category && product.madeFor === madeFor
    );

    return (
        <main className={clsx("px-4 md:px-8 lg:px-20 pt-4")}>
            {/* Overlay (excluding header) */}
            {isCartOpen && (
                <div
                    className="fixed inset-0 bg-[#40424A] opacity-45 z-2"
                    onClick={() => setIsCartOpen(false)}
                />
            )}

            <h1 className="text-3xl font-medium capitalize mb-8">{madeFor}</h1>

            {error ? (
                <p className="text-red-500">Error: {error.message}</p>
            ) : loading ? (
                <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
                    {Array.from({ length: 6 }).map((_, i) => (
                        <ProductCardSkeleton key={i} />
                    ))}
                </div>
            ) : products.length === 0 ? (
                <p>No products found for {madeFor}.</p>
            ) : (
                <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
                    {products.map((product: any) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            )}
            {isCartOpen && (
                <div className="fixed top-18 right-15 z-10 overflow-y-auto max-h-150">
                    <CartOverlay />
                </div>
            )}
        </main>
    );
};

export default ProductListingPage;
