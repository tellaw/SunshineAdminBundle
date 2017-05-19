export const CONTEXT_UPDATE = 'CONTEXT_UPDATE';

export function contextUpdate( entityName, targetId, mode, pageId ) {

    return {
        type: CONTEXT_UPDATE,
        payload: {
            entityName : entityName,
            targetId : targetId,
            mode : mode,
            pageId : pageId
        }
    };
}
