import React from 'react';
import { usePage } from '@inertiajs/react';
export * from './AclMatrix';
const matches=(granted:string,requested:string)=>{if(granted==='*'||granted===requested)return true;if(!granted.includes('*'))return false;const re='^'+granted.split('*').map(s=>s.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')).join('.*')+'$';return new RegExp(re).test(requested)};
export function usePermissions(){const {props}=usePage<any>();const permissions:string[]=props.auth?.permissions??[];const roles:string[]=props.auth?.roles??[];return {permissions,roles,teamId:props.auth?.team??null,can:(p:string)=>permissions.some(g=>matches(g,p)),canAny:(ps:string[])=>ps.some(p=>permissions.some(g=>matches(g,p))),canAll:(ps:string[])=>ps.every(p=>permissions.some(g=>matches(g,p))),hasRole:(r:string)=>roles.includes(r)||roles.includes('*'),hasAnyRole:(rs:string[])=>rs.some(r=>roles.includes(r)),hasAllRoles:(rs:string[])=>rs.every(r=>roles.includes(r))};}
export function Can({permission,children,fallback=null}:{permission:string;children:React.ReactNode;fallback?:React.ReactNode}){return usePermissions().can(permission)?<>{children}</>:<>{fallback}</>}
export function CanAny({permissions,children}:{permissions:string[];children:React.ReactNode}){return usePermissions().canAny(permissions)?<>{children}</>:null}
export function CanAll({permissions,children}:{permissions:string[];children:React.ReactNode}){return usePermissions().canAll(permissions)?<>{children}</>:null}
export function Role({role,children}:{role:string;children:React.ReactNode}){return usePermissions().hasRole(role)?<>{children}</>:null}
