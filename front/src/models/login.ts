export interface LoginPayload extends Login {
    grant_type: 'password'
    client_id: string
    client_secret: ''
}

export interface Login {
    username: string
    password: string
}

export interface ChangePassword {
    password: string
    confirm: string
    token: string
}
