import React from 'react';
import { useQuery } from '@apollo/client';
import { GET_PRODUCTS } from '../graphql/queries/getProducts.ts';
import ProductCard from '../components/ProductCard.tsx';
import ProductCardSkeleton from '../skeletons/ProductCardSkeleton.tsx';
import { useCart } from '../context/CartContext.tsx';
import { CartOverlay } from '../components/CartOverlay/CartOverlay.tsx';

interface ProductListingPageProps {
    category: string;
    madeFor: 'men' | 'women' | 'kids' | 'all';
}

const ProductListingPage: React.FC<ProductListingPageProps> = ({ category, madeFor }) => {
    const { data, loading, error, } = useQuery(GET_PRODUCTS);
    const { isCartOpen, setIsCartOpen } = useCart();

    const allProducts = data?.products ?? [];

    const products =
        category === 'all'
            ? [...allProducts].sort(() => Math.random() - 0.5) 
            : allProducts.filter((product: any) => {
                const categoryMatches = product.category === category;
                const madeForMatches = product.madeFor === madeFor;
                return categoryMatches && madeForMatches;
            });

    const pageTitle = () => {
        if (category === 'clothes') {
            return madeFor;
        }
        if (category === 'tech') {
            return 'Tech';
        }
        if (category === 'all') {
            return 'All';
        }
    }
    

    return (
        <main>

            <h1 className="font-raleway text-3xl font-medium capitalize my-16 mx-20">{pageTitle()}</h1>

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
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 place-items-center">
                    {products.map((product: any) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            )}

            {/* Overlay (excluding header) */}
            {isCartOpen && (
                <>
                    <div
                        className="fixed inset-0 bg-[#40424A] opacity-45 z-2"
                        onClick={() => setIsCartOpen(false)}
                    />
                    <div className="fixed top-18 right-15 z-10 overflow-y-auto max-h-150">
                        <CartOverlay />
                    </div>
                </>
            )}
        </main>
    );
};

export default ProductListingPage;
