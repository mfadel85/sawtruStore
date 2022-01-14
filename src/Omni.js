/**
 * Created by InspireUI on 17/02/2017.
 *
 * @format
 */
import reactotron from "reactotron-react-native";
import { PixelRatio } from "react-native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import store from "@store/configureStore";
import { EventRegister } from 'react-native-event-listeners'
import { Images, Constants, Config } from "@common";
import _Icon from "react-native-vector-icons/MaterialCommunityIcons";
import _IconIO from "react-native-vector-icons/Ionicons";
import _Timer from "react-timer-mixin";
import _Validate from "./ultils/Validate";
import _BlockTimer from "./ultils/BlockTimer";
import _FacebookAPI from "./services/FacebookAPI";


// const { actions: SideMenuActions } = require('@redux/SideMenuRedux')

export const Icon = _Icon;
export const IconIO = _IconIO;
export const EventEmitter = EventRegister;
export const Timer = _Timer;
export const Validate = _Validate;
export const BlockTimer = _BlockTimer;
export const FacebookAPI = _FacebookAPI;
export const Reactotron = reactotron;

const _log = (values) => __DEV__ && reactotron.log(values);
const _warn = (values) => __DEV__ && reactotron.warn(values);
const _error = (values) => __DEV__ && reactotron.error(values);
export function connectConsoleToReactotron() {
  // console.log = _log;
  // console.warn = _warn;
  // console.error = _error;
}
export const log = _log;
export const warn = _warn;
export const error = _error;

/**
 * An async fetch with error catch
 * @param url
 * @param data
 * @returns {Promise.<*>}
 */
export const request = async (url, data = {}) => {
  try {
    const response = await fetch(url, data);

    return await response.json();
  } catch (err) {
    error(err);
    return { error: err };
  }
};

// Drawer
export const openDrawer = () =>
  // EventEmitter.emit(Constants.EmitCode.SideMenuOpen)
  store.dispatch({
    type: Constants.EmitCode.SideMenuOpen,
  });
export const closeDrawer = () =>
  // EventEmitter.emit(Constants.EmitCode.SideMenuClose)
  store.dispatch({
    type: Constants.EmitCode.SideMenuClose,
  });
export const toggleDrawer = () =>
  // EventEmitter.emit(Constants.EmitCode.SideMenuClose)
  store.dispatch({
    type: Constants.EmitCode.SideMenuToggle,
  });

/**
 * Display the message toast-like (work both with Android and iOS)
 * @param msg Message to display
 * @param duration Display duration
 */
export const toast = (msg, duration = 4000) =>
EventRegister.emit(Constants.EmitCode.Toast, msg, duration);

export const getProductImage = (uri, containerWidth) => {
  return uri
  // Enhance number if you want to fetch a better quality image (may affect performance
  const DPI_NUMBER = 0.5; // change this to 1 for high quality image

  if (!Config.ProductSize.enable) {
    return uri;
  }

  if (typeof uri !== "string") {
    return Images.PlaceHolderURL;
  }

  // parse uri into parts
  const index = uri.lastIndexOf(".");
  let editedURI = uri.slice(0, index);
  const defaultType = uri.slice(index);

  const pixelWidth = PixelRatio.getPixelSizeForLayoutSize(containerWidth);

  switch (true) {
    case pixelWidth * DPI_NUMBER < 300:
      editedURI = `${editedURI}-small${defaultType}`;
      break;
    case pixelWidth * DPI_NUMBER < 600:
      editedURI = `${editedURI}-medium${defaultType}`;
      break;
    case pixelWidth * DPI_NUMBER < 1400:
      editedURI = `${editedURI}-large${defaultType}`;
      break;
    default:
      editedURI += defaultType;
  }
  return editedURI;
};

export const getNotification = async () => {
  try {
    const notification = await AsyncStorage.getItem("@notification");
    return JSON.parse(notification);
  } catch (error) {
    // console.log(error);
  }
};
