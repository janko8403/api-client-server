import { Alert, Box, Button } from '@mui/material'
import { changePassword } from 'api/login'
import ButtonWhite from 'components/ButtonWhite'
import Input from 'components/Login/Input'
import LoginWrapper from 'components/Login/LoginWrapper'
import { Errors } from 'models/common'
import React, { useState } from 'react'
import { useMutation } from 'react-query'
import { Link, useNavigate, useSearchParams } from 'react-router-dom'
import { toast } from 'react-toastify'

const inital = { password: '', confirm: '' }

const ChangePassword = () => {
    const [searchParams] = useSearchParams()
    const navigate = useNavigate()
    const [state, setState] = useState(inital)
    const [error, setError] = useState<Errors>({})
    const { isLoading, mutate } = useMutation(changePassword, {
        onSuccess: () => {
            toast.success('Hasło zostało zmienione')
            setState(inital)
            setError({})
            navigate('/')
        },
        onError: (error: any) => {
            if (error.response.data.status === 422) {
                const tmp: any = {}
                Object.entries(error.response.data.validation_messages).forEach(
                    (e) => {
                        tmp[e[0]] = Object.values(e[1] as object)
                    }
                )

                setError(tmp)
            } else {
                toast.error('Wystąpił błąd')
            }
        },
    })

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        mutate({
            password: state.password,
            confirm: state.confirm,
            token: searchParams.get('token')!,
        })
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
                    placeholder="Wprowadź nowe hasło"
                    name="password"
                    autoFocus
                    onChange={(e) =>
                        setState({ ...state, password: e.target.value })
                    }
                    value={state.password}
                    margin="normal"
                    type="password"
                />
                {error.password && (
                    <Alert severity="error">
                        {error.password.join('<br/>')}
                    </Alert>
                )}

                <Input
                    required
                    fullWidth
                    placeholder="Potwierdź nowe hasło"
                    name="confirm"
                    autoFocus
                    onChange={(e) =>
                        setState({ ...state, confirm: e.target.value })
                    }
                    value={state.confirm}
                    margin="normal"
                    type="password"
                />
                {error.confirm && (
                    <Alert severity="error">
                        {error.confirm.join('<br/>')}
                    </Alert>
                )}

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
        </LoginWrapper>
    )
}

export default ChangePassword
