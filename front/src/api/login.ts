import { ChangePassword } from 'models/login'
import api from './api'

export const resetPassword = async (email: string) => {
    const resp = await api.post(`/api/users/reset-password`, { email })

    return resp.data
}

export const changePassword = async ({
    password,
    confirm,
    token,
}: ChangePassword) => {
    const resp = await api.post(`/api/users/change-password?token=${token}`, {
        password,
        confirm,
    })

    return resp.data
}
