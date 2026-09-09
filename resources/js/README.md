# React adapter

Copy/build `src` as an npm package or publish it separately.

```tsx
import {
  Can,
  CanAny,
  CanAll,
  Role,
  usePermissions,
} from '@codesuab/laravel-permission';

<Can permission="users.create">
    <CreateUser />
</Can>

<CanAny permissions={['users.create', 'users.update']}>
    <ManageUsers />
</CanAny>

<Role role="admin">
    <AdminPanel />
</Role>

const { can, canAny, canAll } = usePermissions();
```

The React adapter only controls UI. Backend authorization is mandatory.
