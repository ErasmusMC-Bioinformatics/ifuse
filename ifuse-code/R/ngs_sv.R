setClass("cgsv",representation(assemseq="data.frame",
	transseq="data.frame",intervals="data.frame",
	genes="data.frame",cggenes="data.frame",junctions="data.frame",
	othercg="data.frame",otherannot="data.frame"))






############################################################
##INTERVALS OF LEFT AND RIGHT SECTIONS######################
############################################################
ogdata_se<-startend(ogdata)
print("start ends found")
gc()
############################################################
############################################################


############################################################
##FINDING REGIONS###########################################
############################################################
##with genome table
ucscgenes<-read.table(hgfile,header=T,sep="\t",stringsAsFactors=F)
ucscgenes_list<-list()
chrnames<-c(paste("chr",1:22,sep=""),"chrX","chrY")
for(i in 1:24){
	ucscgenes_list[[i]]<-ucscgenes[ucscgenes$chrom==chrnames[i],]
}
print("ucsc ranges found")
gc()
############################################################


############################################################
##FINDING GENE REGIONS######################################
############################################################
##obtaining intervals (left, right positions) of genes that mapped
leftints<-lapply(ucscgenes_list,subsetintleft,ogdata_se)
rightints<-lapply(ucscgenes_list,subsetintright,ogdata_se)
leftints.df<-do.call(rbind,leftints[!is.na(leftints)])
rightints.df<-do.call(rbind,rightints[!is.na(rightints)])
if(length(grep("\\.",row.names(leftints.df)))>0){
	leftints.df$CG.ID<-sapply(strsplit(row.names(leftints.df),"\\."),"[",1)
	} else leftints.df$CG.ID<-row.names(leftints.df)
if(length(grep("\\.",row.names(rightints.df)))>0){
	rightints.df$CG.ID<-sapply(strsplit(row.names(rightints.df),"\\."),"[",1)
	} else rightints.df$CG.ID<-row.names(rightints.df)
allannot<-merge(leftints.df,rightints.df,by=17,all=T)
allannot.cg<-merge(ogdata_se,allannot,by.x="row.names",by.y=1,all=T)
colnames(allannot.cg)[1]<-"CG.ID"
print("gene regions found")
############################################################
############################################################


############################################################
##EVENTS####################################################
############################################################
data.slot<-new("cgsv",
	assemseq=data.frame(CG.ID=allannot.cg[,1],AssembledSequence=allannot.cg$AssembledSequence),
	transseq=data.frame(CG.ID=allannot.cg[,1],TransitionSequence=allannot.cg$TransitionSequence),
	intervals=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftStart","LeftEnd","RightStart","RightEnd")))],
	genes=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("Left.name",
		"Left.chrom","Left.strand","Left.txStart","Left.txEnd","Right.name",
		"Right.chrom","Right.strand","Right.txStart","Right.txEnd")))],
	cggenes=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftGenes","RightGenes")))],
	junctions=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftChr",
        "LeftPosition","LeftStrand","LeftLength","RightChr","RightPosition",
        "RightStrand","RightLength","StrandConsistent","Interchromosomal",
        "Distance","TransitionLength")))],
	othercg=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("DiscordantMatePairAlignments",
		"JunctionSequenceResolved","LeftRepeatClassification","RightRepeatClassification",
        "XRef","DeletedTransposableElement",
		"KnownUnderrepresentedRepeat","FrequencyInBaselineGenomeSet")))],
	otherannot=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("Left.bin","Left.cdsStart",
        "Left.cdsEnd","Left.exonCount","Left.exonStarts","Left.exonEnds",
        "Left.score","Left.name2","Left.cdsStartStat","Left.cdsEndStat",
        "Left.exonFrames","Right.bin","Right.cdsStart",
        "Right.cdsEnd","Right.exonCount","Right.exonStarts","Right.exonEnds",
        "Right.score","Right.name2","Right.cdsStartStat","Right.cdsEndStat",
        "Right.exonFrames")))])

matched<-cjunct(data.slot@junctions,data.slot@genes,data.slot@otherannot)
gc()
print("associated junctions found")

matchedgenes<-gjunct(matched)
gc()
print("shared genes found")
############################################################
############################################################


############################################################
##ANNOTATION################################################
############################################################
datasgannot<-annotate(matchedgenes)
gc()
print("fusion events annotated 1")

allwithint<-merge(datasgannot,unique(data.slot@intervals),by=1)
names(allwithint)[c(1:7,33:36)]<-paste("Junction",names(allwithint)[c(1:7,33:36)],sep=" ")
names(allwithint)[8:27]<-paste("Gene",names(allwithint)[8:27],sep=" ")
names(allwithint)[28:32]<-c("Associated Junctions","Shared Genes","Gene Mismatch","Single Event","Fusion Gene")

allwithint<-allwithint[,c(1,28:32,22,27,8:12,18:21,13:17,23:26,
	2,4,3,33,34,5,7,6,35,36)] ## just putting in a nicer order

## annotate with in exon yes/no
exoncds_df<-exonannot(allwithint)
geneannotwithexoncds<-cbind(allwithint,exoncds_df)
geneannotwithexoncds<-geneannotwithexoncds[,c(1:6,37:40,7:36)] ## just putting in a nicer order
print("fusion events annotated 2")
gc()

## find related junctions per chromosome
output<-rjunct(geneannotwithexoncds,100)
output<-output[,c(1,41,2:40)]
gc()

## add length, sequence
final1<-merge(output,ogdata[,c(4,8,15,14,24)],by.x=1,by.y="row.names")
names(final1)[42:46]<-paste("Junction",names(final1)[42:46],sep=" ")

## write output
if(length(grep(".csv",fileout))>0){
	write.csv(final1,fileout,row.names=F)
} else write.table(final1,fileout,row.names=F,quote=F,sep="\t")

print("program done")
############################################################
############################################################
