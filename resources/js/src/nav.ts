export function filterByPermission<T extends { permission?: string; role?: string }>(
  items: T[],
  can: (permission: string) => boolean,
  hasRole: (role: string) => boolean,
): T[] {
  return items.filter((item) => {
    if (item.permission && !can(item.permission)) return false;
    if (item.role && !hasRole(item.role)) return false;
    return true;
  });
}
