import { Alert, Box, Button } from '@mui/material'
import { resetPassword } from 'api/login'
import ButtonWhite from 'components/ButtonWhite'
import Input from 'components/Login/Input'
import LoginWrapper from 'components/Login/LoginWrapper'
import React, { useState } from 'react'
import { useMutation } from 'react-query'
import { Link } from 'react-router-dom'
import { toast } from 'react-toastify'

const ResetPassword = () => {
    const [email, setEmail] = useState('')
    const [error, setError] = useState('')
    const { isLoading, mutate } = useMutation(resetPassword, {
        onSuccess: () => {
            toast.success(
                'Na podany adres email został wysłany link do zmiany hasła'
            )
            setEmail('')
            setError('')
        },
        onError: (error: any) => {
            if (error.response.data.status === 422) {
                setError(
                    Object.entries(
                        error.response.data.validation_messages.email
                    )[0][1] as string
                )
            } else {
                setError('Wystąpił błąd')
            }
        },
    })

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        mutate(email)
    }

    return (
        <LoginWrapper>
            <Box
                component="form"
                onSubmit={handleSubmit}
                noValidate
                width="100%"
                mt={1}
                textAlign="center"
            >
                <Input
                    required
                    fullWidth
                    id="email"
                    placeholder="Wprowadź email"
                    name="email"
                    type="email"
                    autoFocus
                    onChange={(e) => setEmail(e.target.value)}
                    value={email}
                />
                <ButtonWhite
                    label="Wyślij"
                    type="submit"
                    disabled={isLoading}
                />
            </Box>
            <Box sx={{ my: 2 }}>
                <Button
                    variant="text"
                    component={Link}
                    to="/"
                    sx={{ textDecoration: 'none', color: '#fff' }}
                >
                    Powrót
                </Button>
            </Box>
            {error && <Alert severity="error">{error}</Alert>}
        </LoginWrapper>
    )
}

export default ResetPassword
