import nltk
import pycrfsuite
import re
import os
import nltk
from nltk.corpus import treebank
from nltk.tag import hmm
from nltk.util import unique_list, LazyMap

#process training data
def preprocess_train():
    train_data = []
    for filename in os.listdir("train"):
        sentence = []
        if filename != ".DS_Store":
            with open("train/"+filename, 'r+', encoding="utf8") as f:
            #with open("train/"+filename, 'r+') as f:
            for line in f.read().splitlines():
                lineSplit = re.split('\t+', line)
                if (len(lineSplit) < 3):
                    continue
                else:
                    sentence.append((lineSplit[0], lineSplit[1], lineSplit[2]))
        train_data.append(sentence)
    return train_data


#process test data
def preprocess_test():
    test_data = []
    for filename in os.listdir("test-public"):
      sent = []
      if filename != ".DS_Store":
        with open("test-public/"+filename, 'r+') as f:
          for line in f.read().splitlines():
            lineSplit = re.split('\t+', line)
            if (len(lineSplit) < 1):
              continue
            else:
              sent.append(lineSplit[0])
        test_data.append(sent)
    return test_data


#Implementation of crf with features

#define features
def featuresets(sent, i):
    word = sent[i][0]
    postag = sent[i][1]
    features = [
        'bias',
        'word.lower=' + word.lower(),
        'word[-3:]=' + word[-3:],
        'word[-2:]=' + word[-2:],
        'word.isupper=%s' % word.isupper(),
        'word.istitle=%s' % word.istitle(),
        'word.isdigit=%s' % word.isdigit(),
        'postag=' + postag,
        'postag[:2]=' + postag[:2],
    ]
    if i > 0:
        word1 = sent[i-1][0]
        postag1 = sent[i-1][1]
        features.extend([
            '-1:word.lower=' + word1.lower(),
            '-1:word.istitle=%s' % word1.istitle(),
            '-1:word.isupper=%s' % word1.isupper(),
            '-1:postag=' + postag1,
            '-1:postag[:2]=' + postag1[:2],
        ])
    else:
        features.append('BOS')

    if i < len(sent)-1:
        word1 = sent[i+1][0]
        postag1 = sent[i+1][1]
        features.extend([
            '+1:word.lower=' + word1.lower(),
            '+1:word.istitle=%s' % word1.istitle(),
            '+1:word.isupper=%s' % word1.isupper(),
            '+1:postag=' + postag1,
            '+1:postag[:2]=' + postag1[:2],
        ])
    else:
        features.append('EOS')

    return features



def sentencefeatures(sent):
    return [featuresets(sent, i) for i in range(len(sent))]

def sentencelabels(sent):
    return [label for token, postag, label in sent]


#train model and tag test data
def train_crf(train_data, test_data):
    X_train = [sentencefeatures(s) for s in train_data]
    y_train = [sentencelabels(s) for s in train_data]

    trainer = pycrfsuite.Trainer(verbose=False)

    for xseq, yseq in zip(X_train, y_train):
        trainer.append(xseq, yseq)

    trainer.train('outmodel')
    tagger = pycrfsuite.Tagger()
    tagger.open('outmodel')


    # Iterate through all sentences in test data output data in format:
    output = []
    tagged = ''
    senlist = []
    sCount = 0

    # sentence-level tagging
    for senten in test_data:
        tagged = tagger.tag(sentencefeatures(senten))
        output.append(tagged)

    for senten in output:
      for wordp in senten:
        if wordp == 'B':
          if sCount not in senlist:
            senlist.append(sCount)
      sCount = sCount + 1

    print(senlist)

    numlist = []
    wCount= 0

    # phrase-level tagging
    for sentence in output:
      for word in sentence:
        if ((word == 'B') or (word == 'I')):
          if wCount not in numlist:
            numlist.append(wCount)
        wCount = wCount + 1
    print(numlist)

    start= numlist[0]
    end= 0
    for i in range(len(numlist)-1):
        if(numlist[i]+1 == numlist[i+1]):
            continue
        else:
            end= numlist[i]
            print(str(start) + '-' + str(end))
            start= numlist[i+1]
    if(i+2 == len(numlist)):
        end= numlist[i+1]
        print(str(start) + '-' + str(end))

def main():
  train_data = preprocess_train()
  test_data = preprocess_test()
  crf_output = train_crf(train_data, test_data)



if __name__ == "__main__":
  main()