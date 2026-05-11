export interface ActivityLog{
    id: number;

    actorId: number | null;
    actorType: string | null;

    event: string;
    resource: string | null;

    context: Array<Object>;
    meta: Array<Object>;

    createdAt: string;
    updatedAt: string;
}