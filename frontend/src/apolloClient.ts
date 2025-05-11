import { ApolloClient, InMemoryCache } from '@apollo/client';

const client = new ApolloClient({
    uri: 'http://davit-harutyunyan.iceiy.com/backend/public/',
    cache: new InMemoryCache(),
});

export default client;
