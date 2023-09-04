import { decode } from 'html-entities';
import striptags from 'striptags';

export const stripTags = (text, allowedTags) => decode(striptags(text, allowedTags));
