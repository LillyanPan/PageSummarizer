"""Simple binary baseline for uncertainty tagging.

Treats each word as a binary classification: is this word inside a CUE or not?

Counts how often each word in the vocabulary appears inside and outside cues,
then normalizes to obtain probabilities. Unseen words are backed off to the
average probability of all observed words.
"""

# Author: Vlad Niculae
# License: Simplified BSD

from __future__ import division, print_function

import os
import fileinput
import glob
from collections import defaultdict
from itertools import groupby, count


def very_naive_predict(files, cue_proba, prior=0.5):
    word_predictions = []
    sentence_predictions = []

    lines = (str.strip(line) for line in fileinput.input(files))
    sentences = (grp for nonempty, grp in groupby(lines, bool) if nonempty)

    word_id = 0

    for sentence_id, sentence in enumerate(sentences):
        sentence_uncertain = False
        sentence_word_predictions = []
        for line in sentence:
            word, tag = line.split("\t")
            proba = cue_proba.get(word, prior)

            if proba > 0.5:
                sentence_word_predictions.append(word_id)
                sentence_uncertain = True

            word_id += 1

        if sentence_uncertain:
            sentence_predictions.append(sentence_id)

        # squeeze word predictions into ranges within sentence
        # e.g. if predictions contains "10, 38, 39, 40, 45"
        # replace with "(10, 10), (38, 40), (45, 45)"

        grp = groupby(sentence_word_predictions,
                      key=lambda item, c=count(): item - next(c))
        for _, g in grp:
            g = list(g)
            word_predictions.append((g[0], g[-1]))

    return word_predictions, sentence_predictions


if __name__ == '__main__':

    from docopt import docopt

    usage = """
    Usage:
        baseline.py [--train DIR --test-public DIR --test-private DIR]

    Options:
        --train=DIR         Training directory. [Default: train]
        --test-public=DIR   Public test directory. [Default: test-public]
        --test-private=DIR  Private test directory. [Default: test-private]
    """

    args = docopt(usage)

    train_dir = args['--train']
    test_public_dir = args['--test-public']
    test_private_dir = args['--test-private']

    train_files = sorted(glob.glob(os.path.join(train_dir, "*.txt")))
    test_public_files = sorted(glob.glob(os.path.join(test_public_dir,
                                                      "*.txt")))
    test_private_files = sorted(glob.glob(os.path.join(test_private_dir,
                                                       "*.txt")))

    label_count = defaultdict(lambda: defaultdict(int))

    for line in fileinput.input(train_files):
        line = line.strip()
        if not line:
            continue

        word, tag, label = line.split("\t")

        # Ignore CUE-id. This is a very simple baseline.
        label = 'CUE' if label.startswith('CUE') else 'OUT'
        label_count[word][label] += 1

    print("Label counts of the word 'some':", label_count["some"])
    print("Label counts of the word 'all':", label_count["all"])

    # normalize to compute the probability that each word is inside a cue

    cue_proba = {word: count['CUE'] / (count['CUE'] + count['OUT'])
                 for word, count in label_count.items()}

    print("Probability of the word 'some' to be in a cue:", cue_proba["some"])
    print("Probability of the word 'all' to be in a cue:", cue_proba["all"])

    prior = sum(cue_proba.values()) / len(cue_proba)

    public_wp, public_sp = very_naive_predict(test_public_files, cue_proba,
                                              prior=prior)
    private_wp, private_sp = very_naive_predict(test_private_files, cue_proba,
                                                prior=prior)

    public_wp_str = " ".join("{}-{}".format(*span) for span in public_wp)
    private_wp_str = " ".join("{}-{}".format(*span) for span in private_wp)

    public_sp_str = " ".join(str(i) for i in public_sp)
    private_sp_str = " ".join(str(i) for i in private_sp)

    with open("baseline_word.csv", "w") as f:
        print("Type,Spans", file=f)
        print("{},{}".format("CUE-public", public_wp_str), file=f)
        print("{},{}".format("CUE-private", private_wp_str), file=f)

    with open("baseline_sent.csv", "w") as f:
        print("Type,Indices", file=f)
        print("{},{}".format("SENTENCE-public", public_sp_str), file=f)
        print("{},{}".format("SENTENCE-private", private_sp_str), file=f)