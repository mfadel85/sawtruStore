/** @format */

import React, { PureComponent } from "react";
import { Events } from "@common";
import { EventRegister } from 'react-native-event-listeners'
import { ModalBox, Review } from "@components";
import styles from "./styles";

class ReviewModal extends PureComponent {
  state = { post: "" };

  componentDidMount() {
    this.sub = Events.onOpenModalReview(this.open);
    Events.onCloseModalReview(this.close);
  }

  componentWillUnmount() {
    this.sub && EventRegister.removeEventListener(this.sub);
  }

  open = (post) => {
    this.setState({ post });
    this.modal && this.modal.openModal();
  };

  close = () => this.modal.closeModalLayout();

  render() {
    return (
      <ModalBox
        css={styles.boxComment}
        isComment
        ref={(modal) => (this.modal = modal)}>
        <Review post={this.state.post} />
      </ModalBox>
    );
  }
}
export default ReviewModal;
