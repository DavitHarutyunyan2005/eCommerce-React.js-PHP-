import { Routes, Route, Navigate } from 'react-router-dom';
import { FC } from 'react';
import ProductListingPage from './components/Product/ProductListingPage.tsx';
import ProductDetailsPage from './components/Product/ProductDetailsPage.tsx';
import Header from './components/Header.tsx';

const App: FC = () => {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<Navigate to="/women" replace />} />
        <Route path="/women" element={<ProductListingPage category="clothes" madeFor="Women" />} />
        <Route path="/men" element={<ProductListingPage category="clothes" madeFor="Men" />} />
        <Route path="/kids" element={<ProductListingPage category="tech" madeFor="Kids" />} />
        <Route path="/women/product/:productId" element={<ProductDetailsPage />} />
        <Route path="/men/product/:productId" element={<ProductDetailsPage />} />
        <Route path="/kids/product/:productId" element={<ProductDetailsPage />} />
      </Routes>
    </>
  );
}

export default App;
