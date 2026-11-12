/**
 * Grid row layouts (project detail page and home page share the keys).
 *
 * box:  wrapper class (must start with "box-", see _ratio.scss)
 * cols: slots or stacks of slots; cls must start with "box__"
 * ratio: crop aspect ratio [w, h] for the slot
 */
const a = (position, extra = '') => ({ position, cls: `box__a${extra}`, ratio: [43, 28] });
const b = (position) => ({ position, cls: 'box__b', ratio: [43, 56] });

export const LAYOUTS = {
    '1fr': { box: 'box-1fr', cols: [{ position: 0, cls: 'box__c', ratio: [86, 56] }] },
    '1fr-wide': { box: 'box-1fr', cols: [{ position: 0, cls: 'box__d', ratio: [86, 28] }] },
    '1fr-portrait': { box: 'box-1fr', cols: [{ position: 0, cls: 'box__e', ratio: [86, 112] }] },
    '2x1fr-wide': { box: 'box-2x1fr', cols: [a(0), a(1)] },
    '2x1fr': { box: 'box-2x1fr', cols: [b(0), b(1)] },
    '2x1fr-logo': { box: 'box-2x1fr', cols: [a(0, ' is-logo'), a(1, ' is-logo')] },
    '1fr-1fr_stacked': { box: 'box-2x1fr', cols: [b(0), { stack: [a(1), a(2)] }] },
    '1fr_stacked-1fr': { box: 'box-2x1fr', cols: [{ stack: [a(0), a(1)] }, b(2)] },
};
