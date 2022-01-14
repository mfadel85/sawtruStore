/** @format */

import React from "react";
import PropTypes from "prop-types";
import { Image } from "react-native";

const ImageCache = ({ style, uri }) => {
  return <Image style={style} source={{ uri }} />;
};

ImageCache.propTypes = {
  style: PropTypes.any,
  uri: PropTypes.any
};

export default ImageCache;
