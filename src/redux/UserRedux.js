/**
 * Created by InspireUI on 14/02/2017.
 *
 * @format
 */
import * as OpencartWorker from '../services/OpencartWorker'
const types = {
  LOGOUT: "LOGOUT",
  LOGIN: "LOGIN_SUCCESS",
  FINISH_INTRO: "FINISH_INTRO",
  UPDATE_BILLING_CUSTOMER: "UPDATE_BILLING_CUSTOMER",
};

export const actions = {
  login: (user, token) => {
    return { type: types.LOGIN, user, token };
  },
  logout() {
    OpencartWorker.logout()
    return { type: types.LOGOUT };
  },
  finishIntro() {
    return { type: types.FINISH_INTRO };
  },
  updateBillingCustomer: (payload) => {
    return { type: types.UPDATE_BILLING_CUSTOMER, payload }
  },
};

const initialState = {
  user: null,
  token: null,
  finishIntro: null,
};

export const reducer = (state = initialState, action) => {
  const { type, user, token } = action;

  switch (type) {
    case types.LOGOUT:
      return Object.assign({}, initialState);
    case types.LOGIN:
      return { ...state, user, token };
    case types.FINISH_INTRO:
      return { ...state, finishIntro: true };
    case types.UPDATE_BILLING_CUSTOMER:
      return { ...state, user: { ...state.user, billing: action.payload } };
    default:
      return state;
  }
};
