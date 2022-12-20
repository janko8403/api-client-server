import { User } from 'models/user'
import api from './api'

export const fetchProfile = async () => {
    const resp = await api.get<User>(`/api/users/profile`)

    return resp.data
}
