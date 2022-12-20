import { CssBaseline } from '@mui/material'
import React from 'react'
import { QueryClient, QueryClientProvider } from 'react-query'
import { ReactQueryDevtools } from 'react-query/devtools'
import { ToastContainer } from 'react-toastify'
import { AuthProvider } from './AuthProvider'
import Router from './Router'
import { SearchProvider } from './SearchProvider'

const queryClient = new QueryClient()

const Providers = () => {
    return (
        <AuthProvider>
            <QueryClientProvider client={queryClient}>
                <CssBaseline />
                <SearchProvider>
                    <Router />
                </SearchProvider>
                <ToastContainer autoClose={2000} />
                <ReactQueryDevtools initialIsOpen={false} />
            </QueryClientProvider>
        </AuthProvider>
    )
}

export default Providers
