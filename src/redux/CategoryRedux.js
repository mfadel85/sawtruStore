/**
 * Created by InspireUI on 14/02/2017.
 *
 * @format
 */

import { Config } from "@common";
// import { warn } from '@app/Omni'
import * as OpencartWorker from '../services/OpencartWorker'

const types = {
  FETCH_CATEGORIES_PENDING: "FETCH_CATEGORIES_PENDING",
  FETCH_CATEGORIES_SUCCESS: "FETCH_CATEGORIES_SUCCESS",
  FETCH_CATEGORIES_FAILURE: "FETCH_CATEGORIES_FAILURE",

  SWITCH_DISPLAY_MODE: "SWITCH_DISPLAY_MODE",
  SET_SELECTED_CATEGORY: "SET_SELECTED_CATEGORY",
  CATEGORY_SELECT_LAYOUT: "CATEGORY_SELECT_LAYOUT",
};

export const DisplayMode = {
  ListMode: "ListMode",
  GridMode: "GridMode",
  CardMode: "CardMode",
};
 
export const actions = {
  fetchCategories: (dispatch) => {
    dispatch({ type: types.FETCH_CATEGORIES_PENDING });
    OpencartWorker.getCategories()
      .then((items) => {
        dispatch(actions.fetchCategoriesSuccess(items));
      })
      .catch((err) => {
        dispatch(actions.fetchCategoriesFailure("Can't get data from server"));
      })
  },
  fetchCategoriesSuccess: (items) => {
    return { type: types.FETCH_CATEGORIES_SUCCESS, items };
  },
  fetchCategoriesFailure: (error) => {
    return { type: types.FETCH_CATEGORIES_FAILURE, error };
  },
  switchDisplayMode: (mode) => {
    return { type: types.SWITCH_DISPLAY_MODE, mode };
  },
  setSelectedCategory: (category) => {
    return { type: types.SET_SELECTED_CATEGORY, category };
  },
  setActiveLayout: (value) => {
    return { type: types.CATEGORY_SELECT_LAYOUT, value };
  },
};

const initialState = {
  isFetching: false,
  error: null,
  displayMode: DisplayMode.GridMode,
  list: [],
  selectedCategory: null,
  selectedLayout: Config.CategoryListView,
};

export const reducer = (state = initialState, action) => {
  const { type, mode, error, items, category, value } = action;

  switch (type) {
    case types.FETCH_CATEGORIES_PENDING: {
      return {
        ...state,
        isFetching: true,
        error: null,
      };
    }
    case types.FETCH_CATEGORIES_SUCCESS: {
      return {
        ...state,
        isFetching: false,
        list: items || [],
        error: null,
      };
    }
    case types.FETCH_CATEGORIES_FAILURE: {
      return {
        ...state,
        isFetching: false,
        list: [],
        error,
      };
    }
    case types.SWITCH_DISPLAY_MODE: {
      return {
        ...state,
        displayMode: mode,
      };
    }
    case types.SET_SELECTED_CATEGORY: {
      return {
        ...state,
        selectedCategory: category,
      };
    }
    case types.CATEGORY_SELECT_LAYOUT:
      return {
        ...state,
        isFetching: false,
        selectedLayout: value || false,
      };

    default: {
      return state;
    }
  }
};
