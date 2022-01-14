/**
 * Created by InspireUI on 14/02/2017.
 *
 * @format
 */

const types = {
  FETCH_TAGS_PENDING: "FETCH_TAGS_PENDING",
  FETCH_TAGS_SUCCESS: "FETCH_TAGS_SUCCESS",
  FETCH_TAGS_FAILURE: "FETCH_TAGS_FAILURE",
};

export const actions = {
  fetchTags: async (dispatch) => {
    dispatch({ type: types.FETCH_TAGS_PENDING });
  },
  fetchTagsSuccess: (items) => {
    return { type: types.FETCH_TAGS_SUCCESS, items };
  },
  fetchTagsFailure: (error) => {
    return { type: types.FETCH_TAGS_FAILURE, error };
  },
};

const initialState = {
  isFetching: false,
  error: null,
  list: [],
};

export const reducer = (state = initialState, action) => {
  const { type, mode, error, items, category, value } = action;

  switch (type) {
    case types.FETCH_TAGS_PENDING: {
      return {
        ...state,
        isFetching: true,
        error: null,
      };
    }
    case types.FETCH_TAGS_SUCCESS: {
      return {
        ...state,
        isFetching: false,
        list: items || [],
        error: null,
      };
    }
    case types.FETCH_TAGS_FAILURE: {
      return {
        ...state,
        isFetching: false,
        list: [],
        error,
      };
    }

    default: {
      return state;
    }
  }
};
