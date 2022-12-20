import { AssemblyOrder, AssemblyOrderStatus } from 'models/assemblyOrders'
import api from './api'

export const fetchAssemblyOrders = async (
    status: AssemblyOrderStatus,
    filters: string[] = []
) => {
    const resp = await api.get<AssemblyOrder[]>(
        `/api/assembly-orders/${status}${
            filters.length ? `?filters=${filters.join(',')}` : ''
        }`
    )

    return resp.data
}

export const fetchAssemblyOrder = async (id: number) => {
    const resp = await api.get<AssemblyOrder>(`/api/assembly-orders/${id}`)

    return resp.data
}

export const hide = async (id: number) => {
    const resp = await api.post(`/api/assembly-orders/hide/${id}`)

    return resp.data
}

export const accept = async (id: number) => {
    const resp = await api.post(`/api/assembly-orders/accept/${id}`)

    return resp.data
}
