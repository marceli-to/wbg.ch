/**
 * API endpoints of the two grid editors (project detail page, home page).
 */
export const MODES = {
    project: {
        isHome: false,
        layouts: 'grid-layouts',
        elements: (gridId) => `grids/${gridId}/elements`,
        destroyElement: (id) => `grid-elements/${id}`,
        imageOf: (e) => e.image,
        // project editor asks before deleting rows only (as before)
        confirmElementDelete: false,
    },
    home: {
        isHome: true,
        layouts: 'home-grid-layouts',
        elements: (gridId) => `home-grids/${gridId}/elements`,
        destroyElement: (id) => `home-grid-elements/${id}`,
        imageOf: (e) => e.projectimage,
        confirmElementDelete: true,
    },
};

/**
 * Normalize API grid elements into {position: element}.
 */
export function normalizeElements(list, mode) {
    const result = {};
    for (const e of list) {
        if (e.news_id && e.news) {
            result[e.position] = {
                id: e.id,
                position: e.position,
                isArticle: true,
                articleId: e.news.id,
                title: e.news.title,
                text: e.news.text,
                link: e.news.link || null,
                linkText: e.news.linkText || null,
            };
        }
        const img = MODES[mode].imageOf(e);
        if (e.project_image_id && img) {
            result[e.position] = {
                id: e.id,
                position: e.position,
                isMedia: true,
                projectId: img.project?.id ?? null,
                image: img.name,
                imageId: img.id,
                caption: img.project?.name || '',
            };
        }
    }
    return result;
}
