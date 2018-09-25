#!/bin/bash
# setup-ssh.sh: load the SSH key

set -ev
declare -r SSH_FILE="$(mktemp -u $HOME/.ssh/travis_rsa)"
# Decrypt the file containing the private key (put the real name of the variables)
openssl aes-256-cbc \
  -K $encrypted_6e055bfe150f_key \
  -iv $encrypted_6e055bfe150f_iv \
  -in ".ci/travis_rsa.enc" \
  -out "$SSH_FILE" -d
# Enable SSH authentication
chmod 600 "$SSH_FILE" \
  && printf "%s\n" \
       "Host github.com" \
       "  IdentityFile $SSH_FILE" \
       "  LogLevel ERROR" >> ~/.ssh/config