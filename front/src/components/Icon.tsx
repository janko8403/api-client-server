import React from 'react'

type Props = {
    name: any
}

const Icon: React.FC<Props> = ({ name }) => {
    const Icon = name
    return <Icon size="1.25rem" />
}

export default Icon
