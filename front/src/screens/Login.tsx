import React, { useContext, useState } from 'react'
import { AuthContext } from 'providers/AuthProvider'
import { Login as LoginType } from 'models/login'
import { Alert, Box, Button } from '@mui/material'
import ButtonWhite from 'components/ButtonWhite'
import LoginWrapper from 'components/Login/LoginWrapper'
import { Link } from 'react-router-dom'
import Input from 'components/Login/Input'

export const Login = () => {
    const [state, setState] = useState<LoginType>({
        username: '',
        password: '',
    })
    const { login, errors, clearErrors } = useContext(AuthContext)

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        clearErrors()
        login(state)
    }

    return (
        <LoginWrapper>
            <Box
                component="form"
                onSubmit={handleSubmit}
                noValidate
                sx={{ mt: 1, textAlign: 'center' }}
            >
                <Input
                    margin="normal"
                    required
                    fullWidth
                    id="username"
                    placeholder="Login"
                    name="username"
                    autoFocus
                    onChange={(e) =>
                        setState({ ...state, username: e.target.value })
                    }
                    value={state.username}
                />
                <Input
                    margin="normal"
                    required
                    fullWidth
                    name="password"
                    placeholder="Hasło"
                    type="password"
                    id="password"
                    onChange={(e) =>
                        setState({ ...state, password: e.target.value })
                    }
                    value={state.password}
                />
                <ButtonWhite label="Zaloguj się" type="submit" />
            </Box>
            <Box sx={{ my: 2 }}>
                <Button
                    variant="text"
                    component={Link}
                    to="/reset-password"
                    sx={{ textDecoration: 'none', color: '#fff' }}
                >
                    Nie pamiętam hasła
                </Button>
            </Box>
            {errors && errors.login && (
                <Alert severity="error">{errors.login[0]}</Alert>
            )}
        </LoginWrapper>
    )
}

export default Login
