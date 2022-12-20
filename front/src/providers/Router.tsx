import Spinner from 'components/Spinner'
import { lazy, Suspense, useContext } from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import { AuthContext } from './AuthProvider'

const Router = () => {
    const { accessToken } = useContext(AuthContext)

    const Layout = lazy(() => import('components/Layout'))
    const Main = lazy(() => import('screens/Main'))
    const AssemblyOrderDetails = lazy(
        () => import('screens/AssemblyOrderDetails')
    )
    const MyWork = lazy(() => import('screens/MyWork'))
    const Profile = lazy(() => import('screens/Profile'))
    const Login = lazy(() => import('screens/Login'))
    const NpsRatingDetails = lazy(() => import('screens/NpsRatingDetails'))
    const ResetPassword = lazy(() => import('screens/ResetPassword'))
    const ChangePassword = lazy(() => import('screens/ChangePassword'))

    return (
        <BrowserRouter>
            <Suspense fallback={<Spinner />}>
                <Routes>
                    {accessToken ? (
                        <Route path="" element={<Layout />}>
                            <Route path="/" element={<Main />} />
                            <Route
                                path="/assembly/:id"
                                element={<AssemblyOrderDetails />}
                            />
                            <Route path="/my-work" element={<MyWork />} />
                            <Route path="/profile" element={<Profile />} />
                            <Route
                                path="/nps-ratings/:id"
                                element={<NpsRatingDetails />}
                            />
                        </Route>
                    ) : (
                        <>
                            <Route path="/" element={<Login />} />
                            <Route
                                path="/reset-password"
                                element={<ResetPassword />}
                            />
                            <Route
                                path="/change-password"
                                element={<ChangePassword />}
                            />
                        </>
                    )}
                </Routes>
            </Suspense>
        </BrowserRouter>
    )
}

export default Router
