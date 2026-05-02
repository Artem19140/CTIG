import { Roles } from "@/constants/Roles";
import { usePage } from "@inertiajs/vue3"

export const useAuth = () => {
    const page = usePage()
    const user  = page.props?.auth?.user ?? null
    const roles = user?.roles ?? [];
    const isSuperAdmin = user?.roles?.includes(Roles.SUPER_ADMIN) ?? false

    const can = (rolesAllowed: Array<Roles>): boolean => {
        if (isSuperAdmin) return true;
        if (!roles.length) return false;
        
        return rolesAllowed.some(item => roles.includes(item))
    };

    const cannot = (rolesAllowed: Array<string>): boolean => {
        if (isSuperAdmin) return false;
        if (!roles.length) return true;
        
        return rolesAllowed.some(item => !roles.includes(item))
    };

    return {can, cannot, user}
}