/** @format */

// @flow
import React, { PureComponent } from "react";
import { Text } from "react-native";
import moment from "moment";

// enable if you would like to load Arabic language
// require('moment/locale/ar');

export default class TimeAgo extends PureComponent {
  props: {
    time: string,
    interval?: number,
    hideAgo?: boolean,
  };
  state: { timer: null | number } = { timer: null };

  static defaultProps = {
    hideAgo: false,
    interval: 60000,
  };

  componentDidMount() {
    this.createTimer();
  }

  createTimer = () => {
    this.setState({
      timer: setTimeout(() => {
        this.update();
      }, this.props.interval),
    });
  };

  componentWillUnmount() {
    clearTimeout(this.state.timer);
  }

  update = () => {
    this.forceUpdate();
    this.createTimer();
  };

  render() {
    const { time, hideAgo } = this.props;
    return <Text {...this.props}>{moment(time).fromNow(hideAgo)}</Text>;
  }
}
