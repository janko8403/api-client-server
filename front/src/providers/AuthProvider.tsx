import React, { useEffect, useState } from 'react'
import { createContext } from 'react'
import axios from 'axios'
import { Errors } from 'models/common'
import { Login, LoginPayload } from 'models/login'

interface Props {
    errors: null | Errors
    accessToken: null | string
    logout: () => void
    clearErrors: () => void
    login: (data: Login) => void
}

export const AuthContext = createContext({} as Props)

export const AuthProvider: React.FC<{ children: React.ReactElement }> = ({
    children,
}) => {
    const [errors, setErrors] = useState<Errors | null>(null)
    const [accessToken, setAccessToken] = useState<string | null>(null)

    useEffect(() => {
        setToken(localStorage.getItem('access_token') || '')
    }, [])

    const setToken = (token: string) => {
        setAccessToken(token)
        localStorage.setItem('access_token', token)
    }

    return (
        <AuthContext.Provider
            value={{
                accessToken,
                logout: () => {
                    setAccessToken(null)
                    localStorage.removeItem('access_token')
                },
                errors,
                clearErrors: () => setErrors(null),
                login: async (data: Login) => {
                    const payload: LoginPayload = {
                        username: data.username,
                        password: data.password,
                        client_id: data.username,
                        client_secret: '',
                        grant_type: 'password',
                    }
                    try {
                        const resp = await axios.post(
                            `${process.env.REACT_APP_API_URL}/oauth`,
                            payload
                        )

                        const token = resp.data.access_token

                        setToken(token)
                    } catch (err: any) {
                        if (
                            err.response.status === 400 ||
                            err.response.status === 401
                        ) {
                            setErrors({
                                login: ['Niepoprawny login lub hasło'],
                            })
                        } else {
                            setErrors(err.response.data)
                        }
                    }
                },
            }}
        >
            {children}
        </AuthContext.Provider>
    )
}
