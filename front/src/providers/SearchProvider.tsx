import React, { useState, createContext } from 'react'

interface Props {
    open: boolean
    filters: string[]
    handleOpen: () => void
    handleClose: () => void
    handleClickItem: (value: string) => void
}

export const SearchContext = createContext({} as Props)

export const SearchProvider: React.FC<{ children: React.ReactElement }> = ({
    children,
}) => {
    const [open, setOpen] = useState(false)
    const [filters, setFilters] = useState<string[]>([])

    return (
        <SearchContext.Provider
            value={{
                open,
                filters,
                handleOpen: () => setOpen(true),
                handleClose: () => setOpen(false),
                handleClickItem: (value: string) => {
                    const currentIndex = filters.indexOf(value)
                    const newChecked = [...filters]

                    if (currentIndex === -1) {
                        newChecked.push(value)
                    } else {
                        newChecked.splice(currentIndex, 1)
                    }

                    setFilters(newChecked)
                },
            }}
        >
            {children}
        </SearchContext.Provider>
    )
}
