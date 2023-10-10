import axios from "axios";

/**
 * Returns the image path for a given image
 * @param image
 * @returns {*}
 */
export function imageLink(image) {
    // remove spaces from image name
    return image.replace(/ /g, '');
}

/**
 * Returns the price format assuming £ for demo
 * @param price
 * @returns {string}
 */
export function getPrice(price) {
    // place decimal point before last two digits
    let pounds = price.slice(0, -2);
    const pence = price.slice(-2);

    let formattedPounds = new Intl.NumberFormat('en-GB');
    formattedPounds = formattedPounds.format(pounds)

    return formattedPounds + '.' + pence
}

/**
 * Returns the short description for a given description
 * @param description
 * @returns {string}
 */
export function shortDescription(description) {
    // return first 100 characters of description, cut off on whole word and add ellipsis
    return description.substring(0, 100).replace(/\w+$/, '') + '...';
}


