/**
 * Created by InspireUI on 06/03/2017.
 *
 * @format
 */

import { Constants, Languages } from "@common";
import moment from "moment";
import * as OpencartWorker from '../services/OpencartWorker'

const types = {
  FETCH_PRODUCTS_PENDING: "FETCH_PRODUCTS_PENDING",
  FETCH_PRODUCTS_SUCCESS: "FETCH_PRODUCTS_SUCCESS",
  FETCH_ALL_PRODUCTS_SUCCESS: "FETCH_ALL_PRODUCTS_SUCCESS",
  FETCH_ALL_PRODUCTS_MORE: "FETCH_ALL_PRODUCTS_MORE",
  FETCH_PRODUCTS_FAILURE: "FETCH_PRODUCTS_FAILURE",
  CLEAR_PRODUCTS: "CLEAR_PRODUCTS",
  INIT_PRODUCTS: "INIT_PRODUCTS",
  FETCH_REVIEWS_PENDING: "FETCH_REVIEWS_PENDING",
  FETCH_REVIEWS_SUCCESS: "FETCH_REVIEWS_SUCCESS",
  FETCH_REVIEWS_FAILURE: "FETCH_REVIEWS_FAILURE",
  FETCH_PRODUCTS_BY_TAGS_PENDING: "FETCH_PRODUCTS_BY_TAGS_PENDING",
  FETCH_PRODUCTS_BY_TAGS_SUCCESS: "FETCH_PRODUCTS_BY_TAGS_SUCCESS",
  FETCH_PRODUCTS_BY_TAGS_FAILURE: "FETCH_PRODUCTS_BY_TAGS_FAILURE",
  FETCH_PRODUCTS_BY_NAME_PENDING: "FETCH_PRODUCTS_BY_NAME_PENDING",
  FETCH_PRODUCTS_BY_NAME_SUCCESS: "FETCH_PRODUCTS_BY_NAME_SUCCESS",
  FETCH_PRODUCTS_BY_NAME_MORE: "FETCH_PRODUCTS_BY_NAME_MORE",
  FETCH_PRODUCTS_BY_NAME_FAILURE: "FETCH_PRODUCTS_BY_NAME_FAILURE",
  FETCH_PRODUCTS_STICKY_PENDING: "FETCH_PRODUCTS_STICKY_PENDING",
  FETCH_PRODUCTS_STICKY_SUCCESS: "FETCH_PRODUCTS_STICKY_SUCCESS",
  FETCH_PRODUCTS_STICKY_FAILURE: "FETCH_PRODUCTS_STICKY_FAILURE",
  FETCH_PRODUCTS_MORE: "FETCH_PRODUCTS_MORE",
  FETCH_PRODUCTS_VARIANT_PENDING: "FETCH_PRODUCTS_VARIANT_PENDING",
  FETCH_PRODUCTS_VARIANT_SUCCESS: "FETCH_PRODUCTS_VARIANT_SUCCESS",
  FETCH_PRODUCTS_VARIANT_FAIL: "FETCH_PRODUCTS_VARIANT_FAIL",
  FETCH_PRODUCTS_RELATED_PENDING: "FETCH_PRODUCTS_RELATED_PENDING",
  FETCH_PRODUCTS_RELATED_SUCCESS: "FETCH_PRODUCTS_RELATED_SUCCESS",
  FETCH_PRODUCTS_RELATED_FAIL: "FETCH_PRODUCTS_RELATED_FAIL",
  GET_COUPON_CODE_PENDING: "GET_COUPON_CODE_PENDING",
  GET_COUPON_CODE_SUCCESS: "GET_COUPON_CODE_SUCCESS",
  GET_COUPON_CODE_FAIL: "GET_COUPON_CODE_FAIL",
  CLEAN_OLD_COUPON: "CLEAN_OLD_COUPON",
  SWITCH_LAYOUT_HOME: "SWITCH_LAYOUT_HOME",
  SAVE_SEARCH_HISTORY: "SAVE_SEARCH_HISTORY",
  CLEAR_SEARCH_HISTORY: "CLEAR_SEARCH_HISTORY",
};

export const actions = {
  fetchProductsByCategoryId: async (
    dispatch,
    categoryId,
    per_page,
    page,
    filters = {}
  ) => {
    dispatch({ type: types.FETCH_PRODUCTS_PENDING });
    OpencartWorker.productsByCategoryId(
      categoryId,
      per_page,
      page,
      filters
    )
      .then((items) => {
        dispatch(actions.fetchProductsSuccess(items));
      })
      .catch((err) => {
        dispatch(actions.fetchProductsFailure("Can't get data from server"));
      })
  },
  fetchProductsSuccess: (items) => ({
    type: types.FETCH_PRODUCTS_SUCCESS,
    items,
    finish: true,
  }),
  fetchProductsFailure: (error) => ({
    type: types.FETCH_PRODUCTS_FAILURE,
    error,
  }),
  clearProducts: () => ({ type: types.CLEAR_PRODUCTS }),
  initProduct: () => ({ type: types.INIT_PRODUCTS }),
  fetchReviewsByProductId: async (dispatch, productId) => {
    dispatch({ type: types.FETCH_REVIEWS_PENDING });
    OpencartWorker.reviewsByProductId(productId)
      .then((items) => {
        dispatch({ type: types.FETCH_REVIEWS_SUCCESS, reviews: items });
      })
      .catch((error) => {
        dispatch({ type: types.FETCH_REVIEWS_FAILURE, message: error });
      })
  },
  fetchProductsByTag: async (dispatch, tag) => {
    dispatch({ type: types.FETCH_PRODUCTS_BY_TAGS_PENDING });
  },

  fetchProductsByName: async (
    dispatch,
    name,
    per_page = 20,
    page = 1,
    filter = {}
  ) => {
    dispatch({ type: types.FETCH_PRODUCTS_BY_NAME_PENDING });
    OpencartWorker.productsByName(name, per_page, page, filter)
      .then((items) => {
        dispatch({
          type:
            page == 1
              ? types.FETCH_PRODUCTS_BY_NAME_SUCCESS
              : types.FETCH_PRODUCTS_BY_NAME_MORE,
          productsByName: items,
          isMore: items.length == per_page,
          currentSearchPage: page,
        })
      })
      .catch((err) => {
        dispatch(actions.fetchProductsFailure("Can't get data from server"))
      })
  },
  fetchStickyProducts: async (dispatch, per_page = 8, page = 1) => {
    dispatch({ type: types.FETCH_PRODUCTS_STICKY_PENDING });
  },
  fetchAllProducts: async (dispatch, per_page = 20, page = 1) => {
    dispatch({ type: types.FETCH_PRODUCTS_PENDING });
    OpencartWorker.getAllProducts(
      per_page,
      page,
      Constants.PostList.order,
      Constants.PostList.orderby
    )
      .then((items) => {
        if (page == 1) {
          dispatch({
            type: types.FETCH_ALL_PRODUCTS_SUCCESS,
            items,
            page,
            finish: items.length == per_page,
          });
        } else {
          dispatch({
            type: types.FETCH_ALL_PRODUCTS_MORE,
            items,
            page,
            finish: items.length == per_page,
          });
        }

      })
      .catch((err) => {
        dispatch({
          type: types.FETCH_PRODUCTS_FAILURE,
          message: "Can't get data from server"
        });
      })
  },
  getProductVariations: async (dispatch, product, per_page = 100, page = 1) => {
    dispatch({ type: types.FETCH_PRODUCTS_VARIANT_PENDING });
  },
  fetchProductRelated: async (dispatch, product) => {
    dispatch({ type: types.FETCH_PRODUCTS_RELATED_PENDING });
    const categories = product.categories;
    // if the product has no categories :)
    console.log('length',categories);
    if (categories.length > 0) {
      const categoryId = categories[0];
      OpencartWorker.productsByCategoryId(categoryId, 10, 1)
        .then((items) => {
          dispatch({
            type: types.FETCH_PRODUCTS_RELATED_SUCCESS,
            productRelated: items,
          });
        })
        .catch((error) => {
          dispatch({
            type: types.FETCH_PRODUCTS_RELATED_FAIL,
            message: error
          });
        })
    } else {
      dispatch({
        type: types.FETCH_PRODUCTS_RELATED_SUCCESS,
        productRelated: [],
      });
    }
  },
  cleanOldCoupon: async (dispatch) => {
    dispatch({ type: types.CLEAN_OLD_COUPON });
  },
  getCouponAmount: async (dispatch, code) => {
    dispatch({ type: types.GET_COUPON_CODE_PENDING });
  },
  switchLayoutHomePage: (layout) => {
    return { type: types.SWITCH_LAYOUT_HOME, layout };
  },
  saveSearchHistory: (dispatch, searchText) => {
    dispatch({ type: types.SAVE_SEARCH_HISTORY, searchText });
  },
  clearSearchHistory: (dispatch) => {
    dispatch({ type: types.CLEAR_SEARCH_HISTORY });
  },
};

const initialState = {
  isFetching: false,
  error: null,
  list: [],
  listAll: [],
  stillFetch: true,
  page: 1,
  layoutHome: Constants.Layout.horizon,

  productFinish: false,
  productsByName: [],
  productSticky: [],
  productVariations: null,

  productRelated: [],
};

export const reducer = (state = initialState, action) => {
  const { type, error, items, page, finish } = action;
  switch (type) {
    case types.FETCH_PRODUCTS_PENDING:
    case types.FETCH_PRODUCTS_BY_TAGS_PENDING:
    case types.FETCH_PRODUCTS_BY_NAME_PENDING:
    case types.FETCH_PRODUCTS_STICKY_PENDING:
    case types.FETCH_PRODUCTS_VARIANT_PENDING:
    case types.FETCH_REVIEWS_PENDING:
    case types.FETCH_PRODUCTS_RELATED_PENDING: {
      return {
        ...state,
        isFetching: true,
        error: null,
        message: "",
      };
    }

    case types.FETCH_PRODUCTS_STICKY_FAILURE:
    case types.FETCH_PRODUCTS_BY_TAGS_FAILURE:
    case types.FETCH_PRODUCTS_BY_NAME_FAILURE:
    case types.FETCH_PRODUCTS_VARIANT_FAIL:
    case types.FETCH_REVIEWS_FAILURE:
    case types.FETCH_PRODUCTS_FAILURE:
    case types.FETCH_PRODUCTS_RELATED_FAIL: {
      return {
        ...state,
        isFetching: false,
        error,
      };
    }

    case types.FETCH_ALL_PRODUCTS_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        listAll: items,
        stillFetch: items.length == Constants.pagingLimit,
        error: null,
        page,
        productFinish: finish,
      });
    }

    case types.FETCH_ALL_PRODUCTS_MORE: {
      return Object.assign({}, state, {
        isFetching: false,
        listAll: state.listAll.concat(items),
        stillFetch: items.length == Constants.pagingLimit,
        error: null,
        page,
        productFinish: finish,
      });
    }

    case types.FETCH_PRODUCTS_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        list: state.list.concat(items),
        stillFetch: items.length == Constants.pagingLimit,
        error: null,
        productFinish: finish,
      });
    }

    case types.CLEAR_PRODUCTS: {
      initialState.listAll = state.listAll;
      initialState.layoutHome = state.layoutHome;
      initialState.productSticky = state.productSticky;
      return Object.assign({}, initialState);
    }

    case types.INIT_PRODUCTS: {
      initialState.layoutHome = state.layoutHome;
      return Object.assign({}, initialState);
    }

    case types.FETCH_REVIEWS_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        reviews: action.reviews,
      });
    }

    case types.FETCH_PRODUCTS_BY_TAGS_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        products: action.products,
      });
    }

    case types.FETCH_PRODUCTS_BY_NAME_SUCCESS: {
      return {
        ...state,
        isFetching: false,
        productsByName: action.productsByName,
        isSearchMore: action.isMore,
        currentSearchPage: action.currentSearchPage,
      };
    }

    case types.FETCH_PRODUCTS_BY_NAME_MORE: {
      return {
        ...state,
        isFetching: false,
        productsByName: state.productsByName.concat(action.productsByName),
        isSearchMore: action.isMore,
        currentSearchPage: action.currentSearchPage,
      };
    }

    case types.FETCH_PRODUCTS_STICKY_SUCCESS: {
      return {
        ...state,
        isFetching: false,
        productSticky: state.productSticky.concat(action.productSticky),
      };
    }

    case types.FETCH_PRODUCTS_VARIANT_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        productVariations: items,
        error: null,
      });
    }

    case types.FETCH_PRODUCTS_RELATED_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        productRelated: action.productRelated,
        error: null,
      });
    }
    case types.GET_COUPON_CODE_SUCCESS: {
      return Object.assign({}, state, {
        isFetching: false,
        coupon: {
          amount: action.amount,
          type: action.discountType,
          code: action.code,
          id: action.id,
        },
        error: null,
      });
    }
    case types.CLEAN_OLD_COUPON: {
      return Object.assign({}, state, {
        coupon: {
          amount: 0,
          code: "",
        },
      });
    }
    case types.SWITCH_LAYOUT_HOME: {
      return {
        ...state,
        layoutHome: action.layout,
      };
    }
    case types.GET_COUPON_CODE_PENDING: {
      return {
        ...state,
        isFetching: true,
        type,
        error: null,
      };
    }
    case types.GET_COUPON_CODE_FAIL: {
      return {
        ...state,
        isFetching: false,
        type,
        message: action.message,
      };
    }
    case types.SAVE_SEARCH_HISTORY: {
      let histories = state.histories;
      if (histories == undefined) {
        histories = [];
      }
      if (histories.indexOf(action.searchText) == -1) {
        histories.unshift(action.searchText);
      }
      if (histories.length > 10) {
        histories.pop();
      }
      return {
        ...state,
        histories,
      };
    }
    case types.CLEAR_SEARCH_HISTORY: {
      return {
        ...state,
        histories: [],
      };
    }
    default: {
      return state;
    }
  }
};
